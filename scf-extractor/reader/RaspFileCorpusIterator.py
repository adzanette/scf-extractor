
from CorpusIterator import *
from FileCorpusIterator import FileCorpusIterator
from models.corpus import Sentence
import re

## Reads a RASP annoted corpus from files
# @author Adriano Zanette
# @version 0.1
class Iterator(CorpusIterator):
  
  ## Class constructor
  # @author Adriano Zanette
  # @version 0.1
  # @return Iterator
  def __init__(self):
    self.startGR = re.compile(r"^gr-list:\s\d+$", re.L)
    self.corpus = FileCorpusIterator()
    self.id = 1
    self.readingSentence = False
 
  ## Get raw sentence based on parsed sentence
  # @author Adriano Zanette
  # @version 0.1
  # @return String
  def getRawSentence(self, line):
    infos = line.strip().split()
    sentence = ''
    if len(infos) > 0:
      for token in infos:
        token = token.translate(None, "()")
        if token.startswith('|'):
          token = token.translate(None, '|')
          sentence = sentence + ' ' + token.split(':')[0]
      
    return sentence

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
      line = line.strip()
      
      if not line and self.readingSentence:
        break
      elif not line:
        continue

      if not self.readingSentence and re.search(self.startGR, line):
        self.readingSentence = True
        continue

      if self.readingSentence:
        parsedSentence = parsedSentence + line + '\n'      
      elif line.strip() and line.startswith('('):
        rawSentence = self.getRawSentence(line)

    self.readingSentence = False

    if parsedSentence.strip() == '':
      return self.next()

    return self.makeSentence(rawSentence, parsedSentence)
