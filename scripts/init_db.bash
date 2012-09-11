#!/bin/bash

rm ../data/db/septa*

sqlite3 ../data/db/septa-data.db < initDb.sql
