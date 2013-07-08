
config = getConfig()
from models.scf import SCF, Element
import re

## Extractor for NILC lexicon
# @author Adriano Zanette
# @version 0.1
class Extractor():

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Extractor
  def __init__(self):
    self.allowedTags = ['BI', 'TI', 'TD', 'AUX', 'INT']
    self.reVerbs = re.compile(r'^(?P<verb>.+)=<V\.\[(?P<subs>[A-Z.]+)\].+N\.\[(?P<preps>[^\]]*)\]', re.L)
    
  ## It extracts frames
  # @author Adriano Zanette
  # @version 0.1
  # @param sentence String 
  # @return Dict Frames to be built
  def extract(self, sentence):
    frames = []
    matches = re.search(self.reVerbs, sentence.raw)
    verb = matches.group("verb")
    subcats = matches.group("subs").split(".")
    prepositions = matches.group("preps")[0:len(matches.group("preps"))-1].split(".")    

    for subcat in subcats:
      if subcat == 'BI' or subcat == 'TI':
        for prep in prepositions:
          frames += self.buildFrame(verb, subcat, prep)
      elif subcat in self.allowedTags:
        frames += self.buildFrame(verb, subcat)  
    
    return frames

  ## Extract information from a token
  # @author Adriano Zanette
  # @version 0.1
  # @param token Token
  # @return Dict Element built
  def buildFrame(self, verb, subcat, prep = None):
    frames = []
    frame = SCF()
    frame.verb = verb
    
    if subcat == "TD": 
      element = Element(sintax = 'NP', element='NP', position = 1, relevance = 1)  
      frame.elements.append(element)   
    elif subcat == 'AUX':
      element = Element(sintax = 'AUX', element='AUX', position = 1, relevance = 1)  
      frame.elements.append(element)   
    elif subcat == 'TI':
      complement = ''
      if prep:
        complement = '[%s]' % (prep)
      element = Element(sintax = 'PP', element='PP%s' % (complement), position = 1, relevance = 2)  
      frame.elements.append(element)   
    elif subcat == 'BI':
      complement = ''
      if prep:
        complement = '[%s]' % (prep)
      element = Element(sintax = 'NP', element='NP', position = 1, relevance = 1)  
      frame.elements.append(element) 
      element = Element(sintax = 'PP', element='PP%s' % (complement), position = 2, relevance = 2)  
      frame.elements.append(element)  
      if config.builder.order == 'position':
        frames.append(frame)

        frame = SCF()
        frame.verb = verb
        element = Element(sintax = 'PP', element='PP%s' % (complement), position = 1, relevance = 2)  
        frame.elements.append(element) 
        element = Element(sintax = 'NP', element='NP', position = 2, relevance = 1)  
        frame.elements.append(element) 
    elif subcat == 'INT':
      element = Element()
    
    frames.append(frame)
    
    return frames    