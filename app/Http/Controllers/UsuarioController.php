<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;


class UsuarioController extends Controller
{
    public function index()
    {
        $usuario = Usuario::all();
        $usuarioCount = Usuario::select('usuario.nome')->count();
        $usuarioAtivo = Usuario::where('usuario.status', '=', 'Ativo')->count();
        return view('usuario', compact('usuario', 'usuarioCount', 'usuarioAtivo'));
    }

    public function create()
    {
        return view('insertUsuario');
    }

    public function insert(Request $request){
        $usuario = new Usuario();
    
        $usuario->nome = $request->txNome;
        $usuario->email = $request->txEmail;
        $usuario->senha = $request->txSenha; // Salva direto em texto puro
        $usuario->status = 'Ativo';
        $usuario->quantiaProjetos = 0;
        $usuario->created_at = date('Y-m-d H:i:s');
        $usuario->updated_at = date('Y-m-d H:i:s');
    
        $usuario->save();
    
        return redirect('/login');
    }

    public function desativar(Request $request, string $id){
        $usuario = Usuario::findOrFail($id);

        $usuario->status = "Inativo";

        $usuario->save();
        return redirect('/usuario');
    }

    public function ativar(Request $request, string $id){
        $usuario = Usuario::findOrFail($id);

        $usuario->status = "Ativo";

        $usuario->save();
        return redirect('/usuario');
    }

    public function editar(Request $request, string $id){
        $usuario = Usuario::findOrFail($id);

        $usuario->nome = $request -> txNome;
        $usuario->email = $request ->txEmail;


        $usuario->save();

        return redirect('/usuario');
    }

    public function excluir(string $id){
        Usuario::where('id', '=', $id)->where('quantiaProjetos', '=', 0)->delete();

        return redirect('/usuario');
    }    

    
    //API

    public function indexAPI(){
        $usuario = Usuario::all();

        return $usuario;
    }

    public function insertAPI(Request $request){
        $usuario = new Usuario();

        $usuario->nome = $request ->nome;
        $usuario->email = $request ->email;
        $usuario->senha = $request ->senha;
        $usuario->status = 'Ativo';
        $usuario->quantiaProjetos = 0;

        $usuario->save();

        return response()->json($usuario, 201);
    }
    public function excluirAPI(string $id){
        $deletado=Usuario::where('id', '=', $id)->where('quantiaProjetos', '=', 0)->delete();

        if($deletado>0){
            return response()->json([
                'message'=> 'Usuário excluído com sucesso',
                'code'=> 200
            ]);
        }else{
            return response()->json([
                'Message'=> 'Não é possível excluir um usuário com projetos vinculados!'
            ]);
        }
    }    
    public function atualizarAPI(Request $request, string $id){
        $validarDados = $request -> validate([
            'nome' => 'min:3',
            'email' => 'max:200',
            'status' => 'in:Ativo,Inativo'
        ]);
        $usuario = Usuario::findOrFail($id);

        $usuario->update($validarDados);

        return response()->json($usuario, 201);
    }

public function showIDAPI($id)
{
    $usuario = Usuario::find($id);

    if (!$usuario) {
        return response()->json(['message' => 'Usuário não encontrado'], 404);
    }

    // Retorna explicitamente apenas o ID do usuário
    return response()->json([
        'id' => $usuario->id
    ], 200);
}


 public function store(Request $request)
 {
    $user = new User();
    $user->name = $request->txNome;
    $user->email = $request->txEmail;
    $user-> password = Hash::make($request->txSenha); 
    $user->created_at = date('Y-m-d');
    $user->updated_at = date('Y-m-d'); 
    $user->save();

    //Auth::login($user);
    return redirect('dashboard')->with('mensagem', 'Usuário criado com sucesso!');

 }



public function fazerLogin(Request $request)
{
    $usuario = Usuario::where('email', $request->email)->first();

    // Compara a senha digitada direto com o texto puro do banco
    if ($usuario && $usuario->senha === $request->password) {
        Auth::login($usuario);
        return redirect('/home');
    }

    return redirect('/login')->with('erro', 'E-mail ou senha inválidos');
}

    public function fazerLogOut(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }

}