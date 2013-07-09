config = getConfig()

from builder.SCFBuilder import Builder as SCFBuilder
from models.scf import *
import operator

## Builder receives as argument an set of elements and builds semantic scfs and stores on database
# @author Adriano Zanette
# @version 0.1
class Builder:

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Builder
  def __init__(self):
    self.order = config.builder.order

  ## Create database structure
  # @author Adriano Zanette
  # @version 0.1
  # @return None
  def createTables(self):
    pass

  ## Delete and create database structure
  # @author Adriano Zanette
  # @version 0.1
  # @return None
  def clearTables(self):
    Example.update(semanticFrame=None).execute()
    SemanticFrame.delete().execute()

  # Builds semantic frames
  # @author Adriano Zanette
  # @version 0.1
  # @param example models.scf.Example 
  # @param frame models.scf.SCF Frames for a sentence
  # @return None
  def buildFrames(self, example, frame):
    scfParts = []
    elements = frame.elements
    elements.sort(key = operator.attrgetter(self.order))

    for element in elements:
      strElement = '%s<%s>' % (element.argument, element.semantic)
      scfParts.append(strElement)
      
    if len(scfParts) > 0:
      scf = scfParts.join('+')
      self.saveFrame(scf, example)

  ## Store Semantic SCF on database 
  # @author Adriano Zanette
  # @version 0.1
  # @param frame String Semantic SCF verb
  # @param example models.scf.Example
  # @return None
  def saveFrame(self, frame, example):
    verb = example.frame.verb
    
    try:
      scf = SemanticFrame.create(frame=frame, verb=verb)
    except:
      SemanticFrame.update(frequency = SemanticFrame.frequency+1).where(SemanticFrame.frame == frame, SemanticFrame.verb == verb).execute()
  
    example.semanticFrame = scf
    example.save()
