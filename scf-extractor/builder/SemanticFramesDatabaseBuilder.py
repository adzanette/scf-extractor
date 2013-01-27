
from models.scf import *
from modules.Configuration import *
from builder.SCFBuilder import Builder as SCFBuilder
import operator

## Builder receives as argument an set of elements and builds semantic scfs and stores on database
# @author Adriano Zanette
# @version 0.1
class Builder:

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

  ## Do nothing, only for avoiding to save sentence
  # @author Adriano Zanette
  # @version 0.1
  # @return None
  def saveSentence(self, sentence):
    pass

  # Builds semantic frames
  # @author Adriano Zanette
  # @version 0.1
  # @param example models.scf.Example 
  # @param frame models.scf.SCF Frames for a sentence
  # @return None
  def buildFrames(self, example, frame):

    elements = frame.elements
   
    scf  = ''
    elements.sort(key = operator.attrgetter(self.order))
    for element in elements:
      strElement = '%s<%s>' % (element.argument, element.semantic)
      if scf  == '' :
        scf = strElement
      else:
        scf += '+' + strElement
  
    if scf <> '':
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
      scf = SemanticFrame.get(SemanticFrame.frame == frame, SemanticFrame.verb == verb)
      scf.frequency = scf.frequency + 1
      scf.save()
    except:
      scf = SemanticFrame.create(frame=frame, verb=verb)

    example.semanticFrame = scf
    example.save()
