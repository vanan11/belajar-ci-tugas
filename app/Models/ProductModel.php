<?php 
namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
	protected $table = 'product'; 
	protected $primaryKey = 'id';
	protected $allowedFields = [
    'nama', 'harga', 'jumlah', 'foto', 'kategori_id', 'created_at', 'updated_at'
];

}