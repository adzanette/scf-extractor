
__all__ = ['Filter']

from modules.Configuration import config
from models.scf import Frame, Verb, ReferenceFrame, database

## This class filter scf based on cutoffs
# @author Adriano Zanette
# @version 1.0
class Filter:

  ## Class constructor
  # @author Adriano Zanette
  # @version 1.0
  # @return Filter
  def __init__(self):
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

  ## update a comparator
  # @author Adriano Zanette
  # @version 1.0
  # @param column String Column name
  # @param operator String A valid comparator in SQL
  # @param value String Value to compare
  def setComparator(self, column, operator, value):
    self.comparators[column] = {
      'operator' : operator,
      'value' : value
    }
 
  ## filter verbs that aren't in a list
  # @author Adriano Zanette
  # @version 1.0
  # @param verbList List A list of verbs
  def filterVerbs(self, verbList):
    Verb.update(filtered = False).execute()
    sql = "UPDATE "+Verb._meta.db_table+" SET "+Verb.filtered.db_column+" = 1 WHERE "+Verb.verb.db_column+" NOT IN ('"+("','".join(verbList))+"') "
    database.execute_sql(sql)

  ## Filters frames based on the list of comparators
  # @author Adriano Zanette
  # @version 1.0
  def filterFrames(self):
    Frame.update(filtered = False).execute()
    sql = "UPDATE "+Frame._meta.db_table+" AS f SET "+Frame.filtered.db_column+" = 1 "+ self.buildWhere()
    database.execute_sql(sql)
  
  ## Builds where clause based on the list of comparators
  # @author Adriano Zanette
  # @version 1.0
  def buildWhere(self):
    where = ''
    first = True
    for field in self.comparators:
      operator = self.comparators[field]['operator']
      value = self.comparators[field]['value']    
      if first:
        where += ' WHERE ('
        first = False
      else:
        where += ' OR '
      where += 'f.' + self.getFieldName(field) + ' ' + self.getExpression(operator, value)
    return where + ')'

  ## Get name of fields in a table
  # @author Adriano Zanette
  # @version 1.0
  # @param field String Field name
  # @return String Fild name on table
  def getFieldName(self, field):
    return getattr(Frame, field).db_column

  ## Get a restriction on sql query
  # @author Adriano Zanette
  # @version 1.0
  # @param operator String Operator type
  # @param value String Value
  # @return String SQL restriction
  def getExpression(self, operator, value):
    operator = operator.strip()

    if operator in ['=', '>', '>=', '<', '<=', '<>']:
      return ' '+ operator +' '+ str(value) + ' '
    elif operator in ['in', 'notin']:
      return ' '+ operator +' ('+ ','.join(value) + ') '
    elif operator == 'between':
      return ' '+ operator +' '+ str(value[0]) + ' AND ' + str(value[1]) + ' '

    return ''
