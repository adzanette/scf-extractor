
from modules.Configuration import *
from builder.SCFBuilder import Builder as SCFBuilder
from models.scf import *
import operator

## Builder for reference frames table
# @author Adriano Zanette
# @version 0.1
class Builder(SCFBuilder):

  ## Create database structure
  # @author Adriano Zanette
  # @version 0.1
  # @return None
  def createTables(self):
    ReferenceFrame.create_table()

  ## Delete database structure
  # @author Adriano Zanette
  # @version 0.1
  # @return None
  def dropTables(self):
    ReferenceFrame.drop_table()

  ## Delete and create database structure
  # @author Adriano Zanette
  # @version 0.1
  # @return None
  def clearTables(self):
    self.dropTables()
    self.createTables()
  
  ## Do nothing, only to avoid to save sentence
  # @author Adriano Zanette
  # @version 0.1
  # @return None
  def saveSentence(self, sentence):
    pass

  ## Stores reference SCF on database 
  # @author Adriano Zanette
  # @version 0.1
  # @param frame models.scf.SCF Subcategorization Frame
  # @param sentence models.scf.Sentence
  # @return None
  def saveFrame(self, frame, sentence):
    try:
      verb = Verb.get(Verb.verb == frame.verb)
    except:
      verb = Verb.create(verb=frame.verb, frequency = 0)
    
    if verb:
      try:
        scf = ReferenceFrame.create(frame=frame.scf, verb=verb)
      except:
        pass
