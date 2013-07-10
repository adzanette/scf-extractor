
from models.scf import SCF, Element

## Extractor for Verbnet reference frames
# @author Adriano Zanette
# @version 0.1
class Extractor():

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Extractor
  def __init__(self):
    pass
    
  ## It extracts reference frames
  # @author Adriano Zanette
  # @version 0.1
  # @param sentence Sentence 
  # @return List Frames to be built
  def extract(self, verbnetItem):
    frames = []

    frames = self.extractVerbClassFrames(verbnetItem)

    subclasses = verbnetItem.findall('SUBCLASSES/VNSUBCLASS') 

    if subclasses:
      frames += [ self.extract(subclass) for subclass in subclasses ]
    
    return frames

  ## Extract informatio from a verbnet class
  # @author Adriano Zanette
  # @version 0.1
  # @param verbClass XML
  # @return Dict Frames to be buiilt
  def extractVerbClassFrames(self, verbClass):

    verbTags = verbClass.findall('MEMBERS/MEMBER') 
    verbs = [ tag.attrib['name'] for tag in verbTags ]
    
    frameTags = verbClass.findall('FRAMES/FRAME/DESCRIPTION') 
    verbnetFrames = [ tag.attrib['primary'] for tag in frameTags ]
    
    frames = [ self.buildFrame(verb, frame) for verb in verbs for frame in verbnetFrames ]

    return frames

  ## Extract information from a verb and frame
  # @author Adriano Zanette
  # @version 0.1
  # @param verb String 
  # @param frame String Verbnet frame
  # @return models.scf.SCF 
  def buildFrame(self, verb, frame):
    
    scf = SCF()
    scf.verb = verb
    
    arguments = frame.upper().split(' ')
    i = 1
    for arg in arguments:
      if arg.startswith('NP'):
        element = Element(sintax = 'NP', element='NP', position = i, relevance = 2)
      elif arg == 'V':
        element = Element(sintax = 'V', element='V', position = i, relevance = 1)
      elif arg.startswith('PP'):
        element = Element(sintax = 'PP', element='PP', position = i, relevance = 3)
      elif arg.startswith('S'):
        element = Element(sintax = 'VP', element='VP', position = i, relevance = 4)
      elif arg.startswith('ADJ'):
        element = Element(sintax = 'ADJP', element='ADJP', position = i, relevance = 5)
      elif arg.startswith('ADV'):
        element = Element(sintax = 'ADVP', element='ADVP', position = i, relevance = 6)
      else:
        continue
      scf.elements.append(element)
      i += 1
      
    return scf    