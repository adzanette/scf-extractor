from models.scf import Frame, database

## This class calculates frame frequency for each frame
# @author Adriano Zanette
# @version 1.0
class FrameFrequency:

  ## calculates frame frequency for each frame
  # @author Adriano Zanette
  # @version 1.0
  @staticmethod
  def calculate():
    print 'Calculating frame frequencies...'
    database.execute_sql("""UPDATE """+Frame._meta.db_table+""" AS f
                              JOIN (
                                    SELECT """+Frame.frame.db_column+""", SUM("""+Frame.frequency.db_column+""") summer
                                    FROM """+Frame._meta.db_table+"""
                                    GROUP BY """+Frame.frame.db_column+"""
                                   ) AS faux
                                ON faux."""+Frame.frame.db_column+""" = f."""+Frame.frame.db_column+"""
                            SET """+Frame.frameFrequency.db_column+""" = faux.summer """)
    
    