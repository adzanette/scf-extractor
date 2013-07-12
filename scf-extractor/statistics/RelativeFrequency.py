from models.scf import Frame, database

## This class calculates relative frequency for each frame
# @author Adriano Zanette
# @version 1.0
class RelativeFrequency:

  ## calculates relative frequency for each frame
  # @author Adriano Zanette
  # @version 1.0
  @staticmethod
  def calculate():
    print 'Calculating relative frequencies...'
    database.execute_sql("""
      UPDATE """+Frame._meta.db_table+""" 
      SET """+Frame.relativeFrequency.db_column+""" = 
        """+Frame.frequency.db_column+"""/"""+Frame.verbFrequency.db_column+""" 
      WHERE 1=1""")
    
    