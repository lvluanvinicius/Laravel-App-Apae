<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transparency extends Model
{
    use HasFactory;

    /**
     * Guarda o nome da tabela referenciada ao modelo.
     *
     * @var string
     */
    protected $table = "transparency";

    /**
     * Guarda o nome dos campos que receberão inserção.
     *
     * @var array
     */
    protected $fillable = [
        "cod_transparency_folders_fk", "filename", "hash", "type_file", "size_file", "ext",
    ];
}