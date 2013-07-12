from models.scf import Frame, database

import warnings, MySQLdb
warnings.filterwarnings("ignore", category=MySQLdb.Warning)

## This class calculates t-score for each frame
# @author Adriano Zanette
# @version 1.0
class TScoreKorhonen:

  ## Calculates t-scores
  # @author Adriano Zanette
  # @version 1.0
  @staticmethod
  def calculate():
    print 'Calculating frame t-score...'    
    sql = """ UPDATE """+Frame._meta.db_table+""" AS f
                JOIN  (
                        SELECT  """+Frame.id.db_column+""",
                                """+Frame.relativeFrequency.db_column+"""+0.0 as p1,
                                """+Frame.verbFrequency.db_column+"""+0.0 as n1,
                                (framesTotal - """+Frame.verbFrequency.db_column+""")+0.0 as n2,
                                (("""+Frame.frameFrequency.db_column+""" - """+Frame.frequency.db_column+""")/(framesTotal - """+Frame.verbFrequency.db_column+"""))+0.0 as p2 
                        FROM """+Frame._meta.db_table+""" 
                        JOIN (
                                SELECT SUM("""+Frame.frequency.db_column+""") AS framesTotal 
                                FROM """+Frame._meta.db_table+""") AS total
                       ) AS faux 
                  ON faux."""+Frame.id.db_column+""" = f."""+Frame.id.db_column+"""
                  SET """+Frame.tscore.db_column+""" = (p1-p2)/SQRT(POW(SQRT(p1*(1-p1)/n1),2) + POW(SQRT(p2*(1-p2)/n2),2))"""
    query = database.execute_sql(sql)
    
    sql = """ UPDATE """+Frame._meta.db_table+""" AS f
                JOIN  (
                        SELECT  MAX("""+Frame.tscore.db_column+""") as maxTscore,
                                MIN("""+Frame.tscore.db_column+""") as minTscore
                        FROM """+Frame._meta.db_table+""" 
                       ) AS faux 
              SET """+Frame.tscore.db_column+""" =  ("""+Frame.tscore.db_column+"""-minTscore) / (maxTscore-minTscore)"""
    query = database.execute_sql(sql)

