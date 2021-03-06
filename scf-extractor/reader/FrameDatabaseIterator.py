
from models.scf import Frame
from DatabaseCorpusIterator import DatabaseCorpusIterator

## This class reads frames from a database
# @author Adriano Zanette
# @version 0.1
class Iterator(DatabaseCorpusIterator):
  
  ## Reads a set of frames from a database
  # @author Adriano Zanette
  # @version 0.1
  # @return peewee.QueryResultWrapper    
  def getRowSet(self):
    frames = Frame.select().limit(self.pageSize).offset(self.last).execute()
    return frames

