
__all__ = ['Sentence']

from modules.Configuration import *
from lib.peewee import *

dbConfig = config.reader.database

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
  raw = TextField(db_column='sentence')
  parsed = TextField(db_column='parsed')  
  html = TextField(db_column='html')

  class Meta:
    db_table = 'sentences'

