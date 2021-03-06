
__all__ = ['Sentence']

from modules.Configuration import config

from lib.peewee import *

try:
  dbConfig = config.corpora.database
except:
  dbConfig = config.frames.database
  dbConfig.table = 'sentences'

if dbConfig.engine == 'mysql':
  host = dbConfig.host
  user = dbConfig.user
  password = dbConfig.password
  dbName = dbConfig.dbName
  database = MySQLDatabase(dbName, user=user, host=host, passwd=password)

## Base Model
# @author Adriano Zanette
# @version 0.1
class BaseModel(Model):
  class Meta:
    database = database

## Model for sentences
# @author Adriano Zanette
# @version 0.1
class Sentence(BaseModel):
  id = PrimaryKeyField(db_column='id')
  code = TextField(db_column="sentence_id")
  raw = TextField(db_column='cleared')
  parsed = TextField(db_column='parsed')  

  class Meta:
    db_table = dbConfig.table

