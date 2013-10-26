
__all__ = ['Sentence']

from modules.Configuration import config

from lib.peewee import *

dbConfig = config.database

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
  sentenceCode = TextField(db_column='sentence_id')
  code = TextField(db_column='code')
  age = TextField(db_column='age')
  role = TextField(db_column='role')
  raw = TextField(db_column='cleared')
  parsed = TextField(db_column='parsed')  
  morph = TextField(db_column='morph')
  dep = TextField(db_column='dep')
  tag = TextField(db_column='tag')

  class Meta:
    db_table = 'utterance_pt'

