
from models.scf import Element, SCF
import re

## Extractor for semantic frames
# @author Adriano Zanette
# @version 0.1
class Extractor():

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Extractor
  def __init__(self):
    pass
  
  ## Extract information from an sintatic scf
  # @author Adriano Zanette
  # @version 0.1
  # @param argument String
  # @return models.scf.Element Element Built
  def buildElement(self, argument):
    
    element = None

    sintax = argument.sintax

    if sintax.startswith('SUBJECT'):
      element = Element(argument = sintax.replace('SUBJECT', 'SUJ'), relevance = 1)
    elif sintax.startswith('REFLEXIVE.OBJECT'):
      element = Element(argument = sintax.replace('REFLEXIVE.OBJECT', 'REFL'), relevance = 3)
    elif sintax.startswith('DIRECT.OBJECT'):
      element = Element(argument = sintax.replace('DIRECT.OBJECT', 'OD'), relevance = 2)
    elif sintax.startswith('INDIRECT.OBJECT'):
      element = Element(argument = sintax.replace('INDIRECT.OBJECT', 'OI'), relevance = 4)
    elif sintax.startswith('ADJUNCT.ADVERBIAL'):
      element = Element(argument = sintax.replace('ADJUNCT.ADVERBIAL', 'AADV'), relevance = 5)
    elif sintax.startswith('PRONOMINAL.INDIRECT.OBJECT'):
      element = Element(argument = sintax.replace('PRONOMINAL.INDIRECT.OBJECT', 'OIP'), relevance = 2)
    elif sintax.startswith('CLAUSAL.DIRECT.OBJECT'):
      element = Element(argument = sintax.replace('CLAUSAL.DIRECT.OBJECT', 'ODO'), relevance = 2)
    elif sintax.startswith('PREDICATIVE'):
      element = Element(argument = sintax.replace('PREDICATIVE', 'PRED'), relevance = 2)
    elif sintax.startswith('PASSIVE.AGENT'):
      element = Element(argument = sintax.replace('PASSIVE.AGENT', 'AP'), relevance = 2)
    elif sintax == 'N' or sintax == 'NUM':
      element = Element(argument = sintax, relevance = 6)
    elif sintax == 'ADJ':
      element = Element(argument = sintax, relevance = 6)
    elif sintax == 'V':
      element = Element(argument = sintax, relevance = 7)
    
    if not element:
      return element

    element.semantic = argument.semantic 
    element.position = argument.position 

    return element

  ## It extracts semantic frames
  # @author Adriano Zanette
  # @version 0.1
  # @param example models.scf.Example 
  # @return models.scf.SCF Frame to be built
  def extract(self, example):
    elements = []

    frame = SCF()
    arguments = example.arguments
    for argument in arguments:
      element = self.buildElement(argument)
      if element:
        frame.elements.append(element)

    return frame


