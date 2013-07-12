from reader import VerbDatabaseIterator
from models.scf import Frame, database
from lib.plfit import plfit

## This class calculates power-law alpha for each verb
# @author Adriano Zanette
# @version 1.0
class PowerLaw:

  ## calculates power-law alpha for each verb
  # @author Adriano Zanette
  # @version 1.0
  @staticmethod
  def calculate():
    print 'Calculating verb power law...'
    for verb in VerbDatabaseIterator():
      sql = "SELECT "+Frame.frequency.db_column+" FROM "+Frame._meta.db_table+" WHERE "+Frame.verb.db_column+" = "+ str(verb.id) +" ORDER BY "+Frame.frequency.db_column
      frequencies = [ row[0] for row in database.execute_sql(sql).fetchall() ]
      try:
        [alpha, xmin, L] = plfit(frequencies, 'finite')
      except:
        alpha = None
      if alpha <> None:
        verb.alpha = alpha
        verb.save()

    
    