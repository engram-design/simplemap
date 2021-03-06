<?php

namespace ether\simplemap\migrations;

use craft\db\Migration;
use craft\db\Query;
use craft\db\Table;
use ether\simplemap\records\Map;

/**
 * m190325_130533_repair_map_elements migration.
 */
class m190325_130533_repair_map_elements extends Migration
{
	/**
	 * @inheritdoc
	 * @return bool
	 * @throws \yii\base\Exception
	 * @throws \yii\base\NotSupportedException
	 */
    public function safeUp()
    {
        if (!$this->db->columnExists(Map::TableName, 'elementId'))
        	return true;

	    echo '    > Start map data fix' . PHP_EOL;

        $rows = (new Query())
	        ->select('*')
	        ->from(Map::TableName)
	        ->all();

        $this->dropTable(Map::TableName);
	    (new Install())->safeUp();

        foreach ($rows as $row)
        {
	        echo '    > Fix map value ' . $row['address'] . PHP_EOL;

	        $record              = new Map();
	        $record->id          = $row['id'];
	        $record->ownerId     = $row['ownerId'];
	        $record->ownerSiteId = $row['ownerSiteId'];
	        $record->fieldId     = $row['fieldId'];

	        $record->lat     = $row['lat'];
	        $record->lng     = $row['lng'];
	        $record->zoom    = $row['zoom'];
	        $record->address = $row['address'];
	        $record->parts   = $row['parts'];

	        $record->save(false);
        }

	    return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m190325_130533_repair_map_elements cannot be reverted.\n";
        return false;
    }
}
