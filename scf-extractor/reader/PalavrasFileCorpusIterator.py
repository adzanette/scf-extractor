
from CorpusIterator import *
from FileCorpusIterator import FileCorpusIterator
from models.corpus import Sentence
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
    self.reTrash = re.compile(r"^\s*<.*?>\s*$", re.L)
    self.corpus = FileCorpusIterator()
    self.id = 1
 
  ## Get the word from a line token
  # @author Adriano Zanette
  # @version 0.1
  # @return Sentence
  def getWord(self, line):
    infos = line.strip().split()
    word = ''
    if len(infos) > 0:
      word = infos.pop(0)
      if word.startswith("$"):
        word = word.replace('$', '')
    
    return word

  ## Create a new sentence
  # @author Adriano Zanette
  # @version 0.1
  # @return Sentence
  def makeSentence(self, raw, parsed):
    sentence = Sentence()
    sentence.id = self.id 
    sentence.raw = raw
    sentence.parsed = parsed
    
    self.id += 1

    return sentence

  ## It gets the next sentence
  # @author Adriano Zanette
  # @version 0.1
  # @return Sentence
  def next(self):

    if self.corpus.EOF:
      raise StopIteration
  
    parsedSentence = ''
    rawSentence = ''
  
    for line in self.corpus:

      if not line or line.strip().startswith('</s>'):
        break

      if re.search(self.reTrash, line):
        continue

      parsedSentence = parsedSentence + line      
      rawSentence = rawSentence + ' ' + self.getWord(line)

    if parsedSentence.strip() == '':
      return self.next()
    return self.makeSentence(rawSentence, parsedSentence)
