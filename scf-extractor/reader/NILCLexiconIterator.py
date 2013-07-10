
from modules.Configuration import config
from models.corpus import Sentence
from CorpusIterator import *
from FileCorpusIterator import FileCorpusIterator
import re


## Reads a NILV lexicon file
# @author Adriano Zanette
# @version 0.1
class Iterator(CorpusIterator):
  
  
  ## Class constructor
  # @author Adriano Zanette
  # @version 0.1
  # @return Iterator
  def __init__(self):
    self.reVerbs = re.compile(r'^(?P<verb>.+)=<V\.\[(?P<subs>[A-Z.]+)\].+N\.\[(?P<preps>[^\]]*)\]', re.L)
    self.id = 1
    path = config.corpora.path  
    self.corpus = open(path)
 
  ## Create a new sentence
  # @author Adriano Zanette
  # @version 0.1
  # @return Sentence
  def makeSentence(self, raw, parsed):
    sentence = Sentence()
    sentence.id = self.id 
    sentence.raw = raw
    sentence.parsed = parsed
    
    self.id = self.id + 1

    return sentence

  ## It gets the next sentence
  # @author Adriano Zanette
  # @version 0.1
  # @return Sentence
  def next(self):

    line = self.corpus.readline()
    if not line:    
      raise StopIteration
    else:
      isVerb = re.search(self.reVerbs, line)
      if isVerb:
        return self.makeSentence(line, line)
      else:
        return self.next()

