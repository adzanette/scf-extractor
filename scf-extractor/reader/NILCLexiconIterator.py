
from FileCorpusIterator import FileCorpusIterator
from models.corpus import Sentence
from CorpusIterator import *
import re


## Reads a PALAVRAS annoted corpus from files
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

    if self.corpus.EOF:
      raise StopIteration
    
    verbLine = ''
    for line in self.corpus:
      isVerb = re.search(self.reVerbs, line)
      if isVerb:
        verbLine = line
        break
    
    return self.makeSentence(verbLine, verbLine)
