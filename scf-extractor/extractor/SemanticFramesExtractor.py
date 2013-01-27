
from models.scf import Element, SCF
import re


class Extractor():

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Extractor
  def __init__(self):
    pass

  def buildElement(self, argument):
    
    element = None

    sintax = argument.sintax

    if sintax.startswith('SUBJECT'):
      element = Element(argument = sintax.replace('SUBJECT', 'SUJ'), relevance = 1)
    elif sintax.startswith('REFLEXIVE.OBJECT'):
      element = Element(argument = sintax.replace('REFLEXIVE.OBJECT', 'REFL'), relevance = 3)
    elif sintax.startswith('DIRECT.OBJECT'):
      element = Element(argument = sintax.replace('DIRECT.OBJECT', 'OBJ.DIR'), relevance = 2)
    elif sintax.startswith('INDIRECT.OBJECT'):
      element = Element(argument = sintax.replace('INDIRECT.OBJECT', 'OBJ.IND'), relevance = 3)
    elif sintax.startswith('ADJUNCT.ADVERBIAL'):
      element = Element(argument = sintax.replace('ADJUNCT.ADVERBIAL', 'ADJ.ADV'), relevance = 6)
    elif sintax == 'N' or sintax == 'NUM':
      element = Element(argument = sintax, relevance = 4)
    elif sintax == 'ADJ':
      element = Element(argument = sintax, relevance = 5)
    elif sintax == 'V':
      element = Element(argument = sintax, relevance = 7)
    
    if not element:
      return element

    element.semantic = argument.semantic 
    element.position = argument.position 

    return element

  def extract(self, example):
    elements = []

    frame = SCF()
    arguments = example.arguments
    for argument in arguments:
      element = self.buildElement(argument)
      if element:
        frame.elements.append(element)

    return frame


