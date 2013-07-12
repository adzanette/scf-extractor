from models.scf import Frame, Verb, database

## This class calculates verb frequency for each frame
# @author Adriano Zanette
# @version 1.0
class VerbFrequency:

  ## calculates verb frequency for each frame
  # @author Adriano Zanette
  # @version 1.0
  @staticmethod
  def calculate():
    print 'Calculating verb frequencies...'
    database.execute_sql("""
      UPDATE """+Frame._meta.db_table+""" AS f 
      SET """+Frame.verbFrequency.db_column+""" = 
          (SELECT """+Verb.frequency.db_column+""" 
           FROM """+Verb._meta.db_table+""" AS v 
           WHERE v."""+Verb.id.db_column+""" = f."""+Frame.verb.db_column+""") 
      WHERE 1=1""")
    