
from models.scf import Frame

## This class builds sql comparators
# @author Adriano Zanette
# @version 1.0
class Comparator:

  ## class constructor
  # @author Adriano Zanette
  # @version 1.0
  # @return Comparator
  def __init__(self, comparator, operator, value):
    self.comparator = comparator
    self.operator = operator
    self.value = value

  ## Get name of the field in the table
  # @author Adriano Zanette
  # @version 1.0
  # @return String Fild name on table
  def getFieldName(self):
    return getattr(Frame, self.comparator).db_column

  ## Get the restriction on sql query
  # @author Adriano Zanette
  # @version 1.0
  # @return String SQL restriction
  def getExpression(self):
    if self.operator in ['=', '>', '>=', '<', '<=', '<>']:
      return ' '+ self.operator +' '+ str(self.value) + ' '
    elif self.operator in ['in', 'notin']:
      return ' '+ self.operator +' ('+ ','.join(self.value) + ') '
    elif self.operator == 'between':
      return ' '+ self.operator +' '+ str(self.value[0]) + ' AND ' + str(self.value[1]) + ' '

    return ''