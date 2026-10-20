<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UsuarioFormRequest;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuario = User::all();
        $usuarioCount = User::count();
        $usuarioAtivo = User::where('status', '=', 'Ativo')->count();
        return view('usuario', compact('usuario', 'usuarioCount', 'usuarioAtivo'));
    }

    public function create()
    {
        return view('insertUsuario');
    }


    public function insert(UsuarioFormRequest $request){
        $usuario = new User();

        $usuario->name = $request->txNome;
        $usuario->email = $request->txEmail;
        $usuario->password = Hash::make($request->txSenha);
        $usuario->status = 'Ativo';
        $usuario->quantiaProjetos = 0;

        $usuario->save();

        // se já estiver logado (criando usuário pelo painel), volta para a lista
        return Auth::check() ? redirect('/usuario') : redirect('/login');
    }

    public function desativar(Request $request, string $id){
        $usuario = User::findOrFail($id);
        $usuario->status = "Inativo";
        $usuario->save();

        return redirect('/usuario');
    }

    public function ativar(Request $request, string $id){
        $usuario = User::findOrFail($id);
        $usuario->status = "Ativo";
        $usuario->save();

        return redirect('/usuario');
    }

    public function editar(Request $request, string $id){
        $usuario = User::findOrFail($id);

        $usuario->name = $request->txNome;
        $usuario->email = $request->txEmail;

        $usuario->save();

        return redirect('/usuario');
    }

    public function excluir(string $id){
        User::where('id', '=', $id)->where('quantiaProjetos', '=', 0)->delete();

        return redirect('/usuario');
    }

    //API

    public function indexAPI(){
        return User::all();
    }

    public function insertAPI(Request $request){
        $usuario = new User();

        $usuario->name = $request->nome;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->senha);
        $usuario->status = 'Ativo';
        $usuario->quantiaProjetos = 0;

        $usuario->save();

        return response()->json($usuario, 201);
    }

    public function excluirAPI(string $id){
        $deletado = User::where('id', '=', $id)->where('quantiaProjetos', '=', 0)->delete();

        if($deletado > 0){
            return response()->json([
                'message' => 'Usuário excluído com sucesso',
                'code' => 200
            ]);
        }else{
            return response()->json([
                'Message' => 'Não é possível excluir um usuário com projetos vinculados!'
            ]);
        }
    }

    public function atualizarAPI(Request $request, string $id){
        $validarDados = $request->validate([
            'nome' => 'min:3',
            'email' => 'max:200',
            'status' => 'in:Ativo,Inativo'
        ]);
        $usuario = User::findOrFail($id);

        $usuario->update($validarDados);

        return response()->json($usuario, 201);
    }

    public function showIDAPI($id)
    {
        $usuario = User::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        // Retorna explicitamente apenas o ID do usuário
        return response()->json([
            'id' => $usuario->id
        ], 200);
    }

    public function fazerLogin(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect('/home');
        }

        return redirect('/login')->with('erro', 'E-mail ou senha inválidos');
    }

    public function fazerLogOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}