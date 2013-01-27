
from models.rasp import *
from models.scf import Element, SCF
import re

## Extractor for RASP dependency format
# @author Adriano Zanette
# @version 0.1
class RaspDependencyExtractor:

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return DatabaseBuilder
  def __init__(self):
    pass

  ## It extracts frames
  # @author Adriano Zanette
  # @version 0.1
  # @param sentence Sentence 
  # @return Dict Frames to be built
  def extract(self, sentence):
    
    try:
      raspSentence = self.buildSentence(sentence)
    except Exception as e:
      print sentence.parsed
      raise e

    verbs = raspSentence.getVerbs()
    frames = []
    for verb in verbs:
      frame = SCF()
      frame.verb = verb.word
      frame.isPassive = verb.isPassive

      verbElement = Element(sintax = 'V', element = 'V', relevance = 0, position = verb.id, raw = verb.word)
      frame.elements.append(verbElement)

      for child in verb.children:
        element = self.buildElement(child)
        if element:
          frame.elements.append(element)
          
      frames.append(frame)

    return frames

  ## Builds a sentence splited in tokens
  # @author Adriano Zanette
  # @version 0.1
  # @param sentence Sentence sentence 
  # @return Sentence
  def buildSentence(self, sentence):
    lines = sentence.parsed.split('\n')

    raspSentence = Sentence(sentence)

    for line in lines:
      line = re.sub('[()|]', '', line.strip())
      args = line.split(" ")
      
      if line == "" or len(args) == 0:
        continue
      raspSentence.addRelationship(args)  
      
    return raspSentence