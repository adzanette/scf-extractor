
from modules.Configuration import *
from models.scf import Frame, Verb, database
from reader import *

__all__ = ['Filter']

class Filter:

  def __init__(self):
    self.query = ''
    self.where = ''
    self.parameters = []
    self.columns = config.filter.columns
    self.operators = config.filter.operators
    self.values = config.filter.values
    self.comparators = {}
    for index, column in enumerate(self.columns):
      self.comparators[column] = {
        'operator' : self.operators[index],
        'value' : self.values[index]
      }

  def filter(self):
    frames = FrameDatabaseIterator.Iterator()
    for frame in frames:
      for attribute in self.comparators:
        operator = self.comparators[attribute]['operator']
        value = self.comparators[attribute]['value']
        if self.testField(self.getField(frame, attribute), operator, value):
          frame.filtered = 1
          frame.save()
          break
  
  def getField(self, obj, field):
    if '.' in field:
      items = field.split('.')
      attribute = items.pop(0)
      obj = getattr(obj, attribute)
      field = '.'.join(items)
      return self.getField(obj, field)
    else:
      return getattr(obj, field)

  def testField(self, field, operator, value):
    operator = operator.strip()

    if operator in ['=', '>', '>=', '<', '<=', '<>']:
      if operator == '=':
        if field == value: return True
      elif operator == '>':
        if field > value: return True
      elif operator == '>=':
        if field >= value: return True  
      elif operator == '<':
        if field < value: return True
      elif operator == '<=':
        if field <= value: return True
      elif operator == '<>':
        if field <> value: return True
    elif operator in ['in', 'notin']:
      if operator == 'in':
        if field in value: return True
      elif operator == 'notin':
        if field not in value: return True
    elif operator == 'between':
      if field >= value[0] and field <= value[1]: return True

    return False

  def resetFilters(self):
    Frame.update(filtered = False).execute()
    self.query = ''
    self.where = ''
    self.parameters = []