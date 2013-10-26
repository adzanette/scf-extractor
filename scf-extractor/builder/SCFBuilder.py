import sys
from modules.Configuration import config
from builder.DatabaseBuilderException import DatabaseBuilderException
from models.scf import *
import operator

## Builder receives as argument an set of elements and builds scfs and stores on database
# @author Adriano Zanette
# @version 0.1
class Builder:

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Builder
  def __init__(self):

    self.order = config.builder.order

    self.ignoreClasses = []
    if config.builder.ignoreClasses:
      self.ignoreClasses = config.builder.ignoreClasses

    self.extractArguments = config.builder.extractArguments

    if config.frames.createTables:
      print 'Creating tables...'
      try:
        self.createTables()
        print 'Tables created!'
      except Exception as e:
        print 'Error creating tables'
        print e

    if config.frames.clearTables:
      print 'truncating tables...'
      try:
        self.clearTables()
        print 'Tables truncated!'
      except Exception as e:
        print 'Error truncating tables'
        print e
        sys.exit()

  ## Builds frames
  # @author Adriano Zanette
  # @version 0.1
  # @param sentence models.scf.Sentence sentence to be stored in database
  # @param frames Dict Frames for a sentence
  # @param order String order to sort list of frame elements
  # @return None
  def buildFrames(self, sentence, frames):

    scfSentence = self.saveSentence(sentence)

    for frame in frames:
      scfParts = []
      
      elements = frame.elements
      elements.sort(key = operator.attrgetter(self.order))
      for element in elements:
        if element.sintax not in self.ignoreClasses:
          scfParts.append(element.element)

      if len(scfParts) == 0:
        scf = 'INTRANS'
      else:
        scf = '_'.join(scfParts)

      frame.scf = scf
      self.saveFrame(frame, scfSentence)

  ## Create database structure
  # @author Adriano Zanette
  # @version 0.1
  # @return None
  def createTables(self):
    Verb.create_table()
    Sentence.create_table()
    Frame.create_table()
    SemanticFrame.create_table()
    Example.create_table()
    Argument.create_table()

  ## Delete database structure
  # @author Adriano Zanette
  # @version 0.1
  # @return None
  def dropTables(self):
    Argument.drop_table()
    Example.drop_table()
    SemanticFrame.drop_table()
    Frame.drop_table()
    Sentence.drop_table()
    Verb.drop_table()

  ## Delete and create database structure
  # @author Adriano Zanette
  # @version 0.1
  # @return None
  def clearTables(self):
    self.dropTables()
    self.createTables()

  ## Saves a sentence on database
  # @author Adriano Zanette
  # @version 0.1
  # @param sentence Sentence to be strored
  # @return None
  def saveSentence(self, sentence):
    if self.extractArguments:
      #return Sentence.create(id = sentence.id, raw = sentence.raw, parsed = sentence.parsed, html = sentence.html)
      return Sentence.create(id = sentence.id, code = sentence.code, raw = sentence.raw, parsed = sentence.parsed)
    else:
      return None
   
  ## Store SCF on database 
  # @author Adriano Zanette
  # @version 0.1
  # @param frame models.scf.SCF Subcategorization Frame
  # @param sentence models.scf.Sentence
  # @return None
  def saveFrame(self, frame, sentence):
    try:
      verb = Verb.create(verb=frame.verb)
    except:
      Verb.update(frequency = Verb.frequency+1).where(Verb.verb == frame.verb).execute()
      verb = Verb.get(Verb.verb == frame.verb)
      
    try:
      scf = Frame.create(frame=frame.scf, verb=verb, isPassive = frame.isPassive)
    except:
      Frame.update(frequency = Frame.frequency+1).where(Frame.frame == frame.scf, Frame.verb == verb, Frame.isPassive == frame.isPassive).execute()
      scf = Frame.get(Frame.frame == frame.scf, Frame.verb == verb, Frame.isPassive == frame.isPassive)

    if self.extractArguments:
      example = Example.create(frame=scf, sentence=sentence, position=frame.position)
      for element in frame.elements:
        Argument.create(
          example = example,
          argument = element.raw,
          element = element.element,
          sintax = element.argument,
          position = element.position,
          positionFirstWord = element.begin,
          positionLasttWord = element.end,
          relevance = element.relevance
        )
