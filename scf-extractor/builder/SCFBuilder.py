config = getConfig()

from models.scf import *
import operator

## Exception for error creating or deleting tables
# @author Adriano Zanette
# @version 0.1
class DatabaseBuilderException(Exception):
  pass

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

    if config.builder.createTables:
      try:
        self.createTables()
      except:
        raise DatabaseBuilderException('Error creating tables')

    if config.builder.clearTables:
      try:
        self.clearTables()
      except:
        raise DatabaseBuilderException('Error truncating tables')

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

      elements = frame.elements
     
      scf  = ''
      elements.sort(key = operator.attrgetter(self.order))
      for element in elements:
        if element.sintax not in self.ignoreClasses:
          if scf  == '' :
            scf = element.element
          else:
            scf += "_" + element.element

      if scf == '':
        scf = 'INTRANS'
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
    #return Sentence.create(id = sentence.id, raw = sentence.raw, parsed = sentence.parsed, html = sentence.html)
    if self.extractArguments:
      return Sentence.create(id = sentence.id, raw = sentence.raw, parsed = sentence.parsed)
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
      verb = Verb.get(Verb.verb == frame.verb)
      verb.frequency = verb.frequency + 1
      verb.save()
    except:
      verb = Verb.create(verb=frame.verb)
      
    try:
      scf = Frame.get(Frame.frame == frame.scf, Frame.verb == verb, Frame.isPassive == frame.isPassive)
      scf.frequency = scf.frequency + 1
      scf.save()
    except:
      scf = Frame.create(frame=frame.scf, verb=verb, isPassive = frame.isPassive)

    if self.extractArguments:
      example = Example.create(frame=scf, sentence=sentence, position=frame.position)
      for element in frame.elements:
        #if element.argument:
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
