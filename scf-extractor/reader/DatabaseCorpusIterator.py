
from modules.Configuration import config
from reader import CorpusIterator

## This class reads sentences from a database
# @author Adriano Zanette
# @version 0.1
class DatabaseCorpusIterator(CorpusIterator):
    
  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return DatabaseIterator
  def __init__(self):
    self.last = 0
    self.rowset = None
    self.pageSize = int(config.reader.pageSize)

  ## Reads a set of sentences from a database
  # @author Adriano Zanette
  # @version 0.1
  # @return peewee.QueryResultWrapper
  def getRowSet(self):
    raise Exception("NotImplementedException")
  
  ## It gets the next item
  # @author Adriano Zanette
  # @version 0.1
  # @return Object
  def next(self):
    try: 
      item = self.rowset.next()
    except:
      self.rowset = self.getRowSet()
      self.last = self.last + self.pageSize
      try:
        item = self.rowset.next()
      except StopIteration:
        raise StopIteration
    
    return item

