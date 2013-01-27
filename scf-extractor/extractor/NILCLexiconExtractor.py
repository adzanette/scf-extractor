
from models.palavras import *
from models.scf import SCF
from modules.Configuration import *
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
    self.reVerbs = re.compile(r'^(?P<verb>.+)=<V\.\[(?P<subs>[A-Z.]+)\].+N\.\[(?P<preps>[^\]]*)\]', re.L)
    
  ## It extracts frames
  # @author Adriano Zanette
  # @version 0.1
  # @param sentence String 
  # @return Dict Frames to be built
  def extract(self, sentence):
    frames = []
    verb = re.search(self.reVerbs, line)
    subcats = verb.group("subs").split(".")
    prepositions = verb.group("preps")[0:len(verb.group("preps"))-1].split(".")    

    for subcat in subcats:
      if subcat == 'BI' or subcat == 'TI'
        for prep in prepositions:
          frames += self.buildFrames(subcat, prep)
      else:
        frames += self.buildFrames(subcat)  
    
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
      element = Element(sintax = 'PP', element='PP[%s]' % (prep), position = 1, relevance = 2)  
      frame.elements.append(element)   
    elif subcat == 'BI':
      element = Element(sintax = 'NP', element='NP', position = 1, relevance = 1)  
      frame.elements.append(element) 
      element = Element(sintax = 'PP', element='PP[%s]' % (prep), position = 2, relevance = 2)  
      frame.elements.append(element)  
      frame.scf = = 'NP_PP[%s]' % (prep)
      if config.builder.order == 'position':
        frames.append(frame)

        frame = SCF()
        frame.verb = verb
        element = Element(sintax = 'PP', element='PP[%s]' % (prep), position = 1, relevance = 2)  
        frame.elements.append(element) 
        element = Element(sintax = 'NP', element='NP', position = 2, relevance = 1)  
        frame.elements.append(element) 
    
    frames.append(frame)
    
    return frames    