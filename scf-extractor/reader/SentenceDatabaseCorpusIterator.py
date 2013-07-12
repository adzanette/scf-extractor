from models.corpus import Sentence
from reader import DatabaseCorpusIterator

## This class reads sentences from a database
# @author Adriano Zanette
# @version 0.1
class SentenceDatabaseCorpusIterator(DatabaseCorpusIterator):
  
  ## Reads a set of sentences from a database
  # @author Adriano Zanette
  # @version 0.1
  # @return peewee.QueryResultWrapper    
  def getRowSet(self):
    sentences = Sentence.select().where( Sentence.parsed <> "").limit(self.pageSize).offset(self.last).execute()
    return sentences

