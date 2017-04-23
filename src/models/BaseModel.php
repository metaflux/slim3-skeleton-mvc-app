<?php

class BaseModel extends \LeanOrm\Model
{

    public function __construct(
        $dsn = '', $uname = '', $paswd = '', array $pdo_driver_opts = [], array $ext_opts = []
    )
    {
        parent::__construct($dsn, $uname, $paswd, $pdo_driver_opts, $ext_opts);

        $col_names = $this->getTableColNames();

        if (in_array('record_creration_date', $col_names)) {

            // this column will be automatically updated
            // when a new record is saved to the database
            $this->_created_timestamp_column_name = 'record_creration_date';
        }

        if (in_array('record_last_modification_date', $col_names)) {

            // this column will be automatically updated
            // when a record (new or existent) is saved to the database
            $this->_updated_timestamp_column_name = 'record_last_modification_date';
        }
    }
}