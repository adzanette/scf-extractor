
__all__ = [
             'Example'
            ,'Frame'
            ,'ReferenceFrame'
            ,'Sentence'
            ,'Verb'
            ,'Argument'
            ,'SemanticFrame'
            ,'Element'
            ,'SCF'
          ]

from modules.Configuration import config
from lib.peewee import *


dbConfig = config.frames.database

if dbConfig.engine == 'mysql':
  host = dbConfig.host
  user = dbConfig.user
  password = dbConfig.password
  dbName = dbConfig.dbName
  database = MySQLDatabase(dbName, user=user, host=host, passwd=password)

## Base Model
# @author Adriano Zanette
# @version 0.1
class BaseModel(Model):
  class Meta:
    database = database

## Model for verbs
# @author Adriano Zanette
# @version 0.1
class Verb(BaseModel):
  id = PrimaryKeyField(db_column='id_verb')
  verb = CharField(max_length=100, unique=True, index=True)
  frequency = IntegerField(default=1, index=True)
  alpha = FloatField(default=0)
  filtered = BooleanField(default=False, index=True)
  
  class Meta:
    db_table = 'verbs'

## Model for frames
# @author Adriano Zanette
# @version 0.1
class Frame(BaseModel):
  id = PrimaryKeyField(db_column='id_frame')
  verb = ForeignKeyField(Verb,  db_column='id_verb', related_name='frames')
  frame = CharField(max_length=255, index=True)
  frequency = IntegerField(default=1, index=True)
  frameFrequency = IntegerField(default=0, db_column='frame_frequency')
  relativeFrequency = FloatField(default=0, db_column='relative_frequency', index=True)
  verbFrequency = IntegerField(default=0, db_column='verb_frequency', index=True)
  logLikelihoodRatio = FloatField(default=0, db_column='log_likelihood_ratio')
  tscore = FloatField(default=0, db_column='t_score')
  isPassive = BooleanField(default=False, db_column='is_passive', index=True)
  filtered = BooleanField(default=False, index=True)

  def referenceFrame(self):
    try:
      return ReferenceFrame.get(ReferenceFrame.verb == self.verb, ReferenceFrame.frame == self.frame)
    except ReferenceFrame.DoesNotExist:
      return None
  
  class Meta:
    db_table = 'frames'
    indexes = (
                (('frame', 'verb', 'isPassive'), True),
              )

## Model for reference frames
# @author Adriano Zanette
# @version 0.1
class ReferenceFrame(BaseModel):
  id = PrimaryKeyField(db_column='id_frame')
  verb = ForeignKeyField(Verb,  db_column='id_verb', related_name='referenceFrames')
  frame = CharField(max_length=255, index=True)
  isPassive = BooleanField(default=False, db_column='is_passive', index=True)
  
  class Meta:
    db_table = config.frames.referenceTable
    indexes = (
                (('frame', 'verb'), True),
              )

## Model for semantic frames
# @author Adriano Zanette
# @version 0.1
class SemanticFrame(BaseModel):
  id = PrimaryKeyField(db_column='id_frame')
  verb = ForeignKeyField(Verb,  db_column='id_verb', related_name='semanticFrames')
  frame = CharField(max_length=255)
  frequency = IntegerField(default=1)
  
  class Meta:
    db_table = 'semantic_frames'
    indexes = (
                (('frame', 'verb'), True),
              )

## Model for sentences
# @author Adriano Zanette
# @version 0.1
class Sentence(BaseModel):
  id = PrimaryKeyField(db_column='id_sentence')
  code = CharField(max_length=100, null=True)
  raw = TextField(db_column='raw_sentence')
  parsed = TextField(db_column='parsed_sentence')  
  html = TextField(db_column='html_sentence', null=True)
  class Meta:
    db_table = 'sentences'

## Model for frame examples
# @author Adriano Zanette
# @version 0.1
class Example(BaseModel):
  id = PrimaryKeyField(db_column='id_example')
  sentence = ForeignKeyField(Sentence, db_column='id_sentence', related_name='examples')
  frame = ForeignKeyField(Frame, db_column='id_frame', related_name='examples')
  semanticFrame = ForeignKeyField(SemanticFrame, db_column='id_semantic_frame', null=True, related_name='examples')
  position = IntegerField()
  active = BooleanField(default=True)

  class Meta:
    db_table = 'examples'

## Model for frame arguments
# @author Adriano Zanette
# @version 0.1
class Argument(BaseModel):
  id = PrimaryKeyField(db_column='id_argument')
  example = ForeignKeyField(Example, db_column='id_example', related_name='arguments')
  argument = TextField()
  sintax = CharField(max_length=50, null=True)
  element = CharField(max_length=50, null=True)
  semantic = CharField(max_length=50, null=True)
  position = IntegerField(db_column='sentence_position')
  positionFirstWord = IntegerField(db_column='position_first_word')
  positionLasttWord = IntegerField(db_column='position_last_word')
  relevance = IntegerField(db_column='relevance')
  active = BooleanField(default=True)

  class Meta:
    db_table = 'arguments'

## Model for frame elements
# @author Adriano Zanette
# @version 0.1
class Element():
  
  def __init__(self, **kwargs):
    self.element = ''
    self.sintax = ''
    self.semantic = ''
    self.argument = ''
    self.raw = ''
    self.relevance = 1
    self.position = 1
    self.begin = 1
    self.end =1
    attributes = set(self.__dict__)
    for key, value in kwargs.iteritems():
      if key in attributes:
        setattr(self, key, value)

## Model for scf
# @author Adriano Zanette
# @version 0.1
class SCF():
  
  def __init__(self):
    self.scf = ''
    self.verb = ''
    self.position = -1
    self.isPassive = False
    self.elements = []
