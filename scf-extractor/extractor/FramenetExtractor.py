
from modules.Configuration import *
from modules.XmlUtils import XmlUtils
from models.scf import SCF, Element
from models.palavras import *
import re

## Extractor for PALAVRAS dependency format
# @author Adriano Zanette
# @version 0.1
class Extractor():

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Extractor
  def __init__(self):
    self.namespace = 'http://framenet.icsi.berkeley.edu'
  
  ## It extracts frames
  # @author Adriano Zanette
  # @version 0.1
  # @param framenetItem XMLNode 
  # @return Dict Frames to be built
  def extract(self, framenetItem):
    frames = []

    sintax = framenetItem.attrib['POS']
    if sintax <> 'V':
      return frames

    lexeme = XmlUtils.findall(self.namespace, framenetItem, 'lexeme')[0].attrib['name']   
    
    verb = XmlUtils.findall(self.namespace, framenetItem, 'lexeme')[0].attrib['name']
    scfs = XmlUtils.findall(self.namespace, framenetItem, './valences/FEGroupRealization/pattern')
    
    for scf in scfs:
      frame = SCF()
      frame.verb = verb
      scfElements = XmlUtils.findall(self.namespace, scf, './valenceUnit')
      i = 1
      for scfElement in scfElements:
        element = self.buildElement(scfElement) 
        if element:
          element.position = i
          frame.elements.append(element)
          i += 1
      frames.append(frame)
    
    return frames
      

  ## Extract information from a token
  # @author Adriano Zanette
  # @version 0.1
  # @param xmlElement XML element
  # @return models.scf.Element Element built
  def buildElement(self, xmlElement):
    sintax = xmlElement.attrib['PT']
    semantic = xmlElement.attrib['FE']
    elementType = xmlElement.attrib['GF']
    
    element = None

    if sintax in ['POSS', 'N', 'NP']:
      element = Element(sintax = 'NP', element = 'NP', relevance = 1)
    elif sintax.startswith('PP'):
      sintax = sintax.replace('PPing', 'PP')
      sintax = sintax.replace('PPinterrog', 'PP')
      element = Element(sintax = 'PP', element = sintax, relevance = 1)
    elif sintax.startswith('VP'):
      element = Element(sintax = 'VP', element = "VP", relevance = 1)
    elif sintax in ['A', 'AJP']:
      element = Element(sintax = 'ADJP', element = "ADJP", relevance = 1)
    elif sintax == 'AVP':
      element = Element(sintax = 'ADVP', element = "ADVP", relevance = 1)
    
    if not element:
      return element
        
    return element