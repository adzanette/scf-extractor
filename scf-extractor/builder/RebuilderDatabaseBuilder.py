from modules.Configuration import config
from builder import SCFBuilder
from models.scf import Example, Frame
import operator

## Builder receives as argument an set of elements and builds semantic scfs and stores on database
# @author Adriano Zanette
# @version 0.1
class ReDatabaseBuilder:

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
    Example.update(frame=None).execute()
    Frame.delete().execute()

  ## Do nothing, only to avoid to save sentence
  # @author Adriano Zanette
  # @version 0.1
  # @return None
  def saveSentence(self, sentence):
    pass

  ## Store SCF on database 
  # @author Adriano Zanette
  # @version 0.1
  # @param frame models.scf.SCF Subcategorization Frame
  # @param sentence models.scf.Sentence
  # @return None
  def saveFrame(self, frame, sentence):
    try:
      verb = Verb.get(Verb.verb == frame.verb)
    except:
      return None
            
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

