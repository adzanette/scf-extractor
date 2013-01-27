from lib.peewee import *
from modules.Configuration import *

__all__ = ['Sentence']

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
  id = PrimaryKeyField(db_column='ID')
  raw = TextField(db_column='Sentence')
  parsed = TextField(db_column='RaspDepParse')  

  class Meta:
    db_table = 'sentences'

