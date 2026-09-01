<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    protected $table = 'tarefa';

    protected $fillable = [
        'titulo',
        'descricao',
        'status',
        'data_inicio',
        'data_fim',
        'usuario_id',
        'projeto_id'
    ];
}