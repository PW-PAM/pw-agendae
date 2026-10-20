<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Projeto;
use App\Models\User;

class ProjetoController extends Controller
{
    public function index(){
        $projeto = Projeto::join('users', 'projeto.usuario_id', '=', 'users.id')
            ->select('projeto.*', 'users.name as usuario_nome')
            ->get();

        $projetoCount = Projeto::count();

        return view('projetos', compact('projeto', 'projetoCount'));
    }

    public function create(){
        return view('projetos');
    }

    public function projeto_select(){
        $usuario = User::all();

        return view('insertProjeto', compact('usuario'));
    }

    public function insert(Request $request){
        $projeto = new Projeto();

        $projeto->nome = $request->txNome;
        $projeto->descricao = $request->txDesc;
        $projeto->quantiaTarefas = 0;
        $projeto->usuario_id = $request->txUser;
        $projeto->created_at = date('Y-m-d H:i:s');
        $projeto->updated_at = date('Y-m-d H:i:s');

        $projeto->save();

        $usuario = User::findOrFail($request->txUser);

        $usuario->quantiaProjetos = Projeto::where('usuario_id', '=', $usuario->id)->count();
        $usuario->save();

        return redirect('/projeto');
    }

    public function editar(Request $request, string $id){
        $projeto = Projeto::findOrFail($id);

        $projeto->nome = $request->txNome;
        $projeto->descricao = $request->txDesc;

        $projeto->save();

        return redirect('/projeto');
    }

    public function excluir(Request $request, string $id){
        $projeto = Projeto::findOrFail($id);
        $usuario = User::findOrFail($projeto->usuario_id);

        Projeto::where('id', '=', $id)->where('quantiaTarefas', '=', 0)->delete();

        $usuario->quantiaProjetos = Projeto::where('usuario_id', '=', $usuario->id)->count();
        $usuario->save();

        return redirect('/projeto');
    }

    //API

    public function indexAPI(){
        return Projeto::join('users', 'projeto.usuario_id', '=', 'users.id')
            ->select('projeto.*', 'users.name as usuario_nome')
            ->get();
    }

    public function insertAPI(Request $request){
        $projeto = new Projeto();

        $projeto->nome = $request->nome;
        $projeto->descricao = $request->descricao;
        $projeto->quantiaTarefas = 0;
        $projeto->usuario_id = $request->usuario_id;

        $projeto->save();

        $usuario = User::findOrFail($request->usuario_id);

        $usuario->quantiaProjetos = Projeto::where('usuario_id', '=', $usuario->id)->count();
        $usuario->save();

        return response()->json($projeto, 201);
    }

    public function excluirAPI(Request $request, string $id){
        $projeto = Projeto::findOrFail($id);
        $usuario = User::findOrFail($projeto->usuario_id);

        $deletado = Projeto::where('id', '=', $id)->where('quantiaTarefas', '=', 0)->delete();

        $usuario->quantiaProjetos = Projeto::where('usuario_id', '=', $usuario->id)->count();
        $usuario->save();

        if($deletado > 0){
            return response()->json([
                'message' => 'Projeto excluído com sucesso',
                'code' => 200
            ]);
        }else{
            return response()->json([
                'Message' => 'Não é possível excluir um projeto com tarefas vinculadas!'
            ]);
        }
    }

    public function atualizarAPI(Request $request, string $id){
        $validarDados = $request->validate([
            'nome' => 'min:3',
            'descricao' => 'max:200',
        ]);
        $projeto = Projeto::findOrFail($id);

        $projeto->update($validarDados);

        return response()->json($projeto, 201);
    }
}