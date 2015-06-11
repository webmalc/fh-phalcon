<?php
namespace FH\Models;

use \Phalcon\Mvc\Model;
use \Phalcon\Logger;

/**
 * Base frontend model
 */
class Base extends Model implements \JsonSerializable
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @var \DateTime
     */
    public $updatedAt;

    /**
     * @var boolean
     */
    public $active = true;

    /**
     * Return excluded fields for setData method
     * @return string[]
     */
    protected function getExcludeFields()
    {
        return [
            'id', 'updatedAt'
        ];
    }
    /**
     * Set entity data from array
     * @param array $data
     * @param array $exclude fields for excluding
     * @return \FH\Models\Base
     */
    public function setData(array $data, $exclude = [])
    {
        $exclude = array_merge($exclude, $this->getExcludeFields());
        $reflect = new \ReflectionObject($this);
        foreach ($reflect->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            $propName = $prop->getName();
            if ($propName == 'id' || in_array($propName, $exclude)) {
                continue;
            }
            if (isset($data[$propName])) {
                $this->$propName = $data[$propName];
            }
        }
        return $this;
    }

    /**
     * Serialize object to json
     * @return array
     */
    public function jsonSerialize()
    {
        $result = [];
        $reflect = new \ReflectionObject($this);
        foreach ($reflect->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            $propName = $prop->getName();
            $value = $this->$propName;
            if ($value instanceof \DateTime) {
                $value = $value->format('U') * 1000;
            }
            $result[$propName] = $value;
        }
        return $result;
    }

    /**
     * Before create event
     */
    public function beforeCreate()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = $this->convertDate(new \DateTime());
        }
    }

    /**
     * Before update event
     */
    public function beforeUpdate()
    {
        if (empty($this->updatedAt)) {
            $this->updatedAt = $this->convertDate(new \DateTime());
        }
    }

    /**
     * Before save event
     */
    public function beforeSave()
    {
        $reflect = new \ReflectionObject($this);
        foreach ($reflect->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            $propName = $prop->getName();
            $this->$propName;

            //convert arrays
            if (is_array($this->$propName)) {
                $this->$propName = $this->convertArray($this->$propName);
            }

            //convert DateTime
            if ($this->$propName instanceof \DateTime) {
                $this->$propName = $this->convertDate($this->$propName);
            }
        }
    }

    /**
     * After fetch event
     */
    public function afterFetch()
    {
        $reflect = new \ReflectionObject($this);
        foreach ($reflect->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            $propName = $prop->getName();
            $this->$propName;

            $res = null;
            preg_match('/\@var (.+)/ium', $prop->getDocComment(), $res);

            if(empty($res[1])) {
                continue;
            }

            if ($res[1] == 'array') {
                $this->$propName = $this->convertArray($this->$propName);
            }

            if ($res[1] == '\DateTime') {
                $this->$propName = $this->convertDate($this->$propName);
            }
        }
    }

    /**
     * Converts array to sql format
     * @param mixed $value
     * @return null|string|array
     */
    public function convertArray($value)
    {
        if (empty($value)) {
            return null;
        }
        // serialize array
        if (is_array($value)) {

            return serialize($value);
        }

        return unserialize($value);
    }

    /**
     * Converts date to \DateTime or string
     * @param mixed $value
     * @return null|string|\DateTime
     */
    public function convertDate($value)
    {
        if (empty($value)) {
            return null;
        }

        // Convert DateTime to string
        if ($value instanceof \DateTime) {
            $value->setTimezone(new \DateTimeZone('UTC'));
            return $value->format('Y-m-d H:i:s');
        }

        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $value, new \DateTimeZone('UTC'));
        $date->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        return $date;
    }

    /**
     * After save action
     */
    public function afterSave()
    {
        // Create log
        $this->getDI()->get('logger')->log('Saved entry with class ' . get_class($this) . ' and id #' . $this->id, Logger::INFO);
    }

    /**
     * Before delete action
     */
    public function beforeDelete()
    {
        // Create log
        $this->getDI()->get('logger')->log('Deleted entry with class ' . get_class($this) . ' and id #' . $this->id, Logger::INFO);
    }
}