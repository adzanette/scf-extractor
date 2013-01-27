
from models.scf import Example
from DatabaseCorpusIterator import DatabaseCorpusIterator

## This class reads examples from a database
# @author Adriano Zanette
# @version 0.1
class Iterator(DatabaseCorpusIterator):
  
  ## Reads a set of examples from a database
  # @author Adriano Zanette
  # @version 0.1
  # @return peewee.QueryResultWrapper    
  def getRowSet(self):
    examples = Example.select().where( Example.active == True ).limit(self.pageSize).offset(self.last).execute()
    return examples

