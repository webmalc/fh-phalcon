<?php
namespace FH\Models;

use \Phalcon\Mvc\Model;
use \Phalcon\Mvc\Model\Query;

/**
 * FinancesTag model
 */
class FinancesTag extends Model implements \JsonSerializable
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @param $search
     * @return array
     */
    public function getDistinct($search)
    {
        $result = [];
        $query = new Query("SELECT DISTINCT title FROM FH\Models\FinancesTag WHERE title LIKE :search:", $this->getDI());
        foreach($query->execute(['search' => '%' . trim($search) . '%']) as $row) {
            $result[] = $row->title;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return 'finances_tags';
    }

    public function initialize()
    {
        $this->belongsTo('finances_id', '\FH\Models\Finances', 'id', ['alias' => 'finances']);
    }

    /**
     * Serialize object to json
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'title' => $this->title
        ];
    }
}