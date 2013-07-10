
__all__ = ['Filter']

from modules.Configuration import config
from modules.Comparator import Comparator
from models.scf import Frame, Verb, database

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
    self.comparators = {}
    
    columns = config.filter.columns
    operators = config.filter.operators
    values = config.filter.values
    for index, column in enumerate(columns):
      column = column.strip()
      self.comparators[column] = Comparator(column, operators[index].strip(), values[index])

  ## update a comparator
  # @author Adriano Zanette
  # @version 1.0
  # @param column String Column name
  # @param operator String A valid comparator in SQL
  # @param value String Value to compare
  def setComparator(self, column, operator, value):
    self.comparators[column] = Comparator(column, operator, value)
 
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
  def buildWhere(self, useWhere = True):
    
    expressions = [ ' f.%s %s ' % (comparator.getFieldName(), comparator.getExpression()) for comparator in self.comparators.itervalues() ]
    where = '(' + ' OR '.join(expressions) + ')'
    if useWhere:
      where =  ' WHERE  %s ' % (where)

    return where

 

