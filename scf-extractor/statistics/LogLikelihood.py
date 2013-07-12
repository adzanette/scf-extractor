from reader.FrameDatabaseIterator import Iterator as FrameIterator
from models.scf import Frame, database
import math

import warnings, MySQLdb
warnings.filterwarnings("ignore", category=MySQLdb.Warning)

## This class calculates loglikelihodd for each frame
# @author Adriano Zanette
# @version 1.0
class LogLikelihood:

  ## Calculates loglikelihood for each frame
  # @author Adriano Zanette
  # @version 1.0
  @staticmethod
  def calculate():
    print 'Calculating frame loglikelihood...'
    
    sql = """ UPDATE """+Frame._meta.db_table+""" AS f
                JOIN  (
                        SELECT  """+Frame.id.db_column+""",
                                """+Frame.relativeFrequency.db_column+"""+0.0 as p1,
                                """+Frame.frequency.db_column+"""+0.0 as k1,
                                """+Frame.verbFrequency.db_column+"""+0.0 as n1,
                                ("""+Frame.frameFrequency.db_column+""" - """+Frame.frequency.db_column+""")+0.0 as k2,
                                (framesTotal - """+Frame.verbFrequency.db_column+""")+0.0 as n2,
                                (("""+Frame.frameFrequency.db_column+""" - """+Frame.frequency.db_column+""")/(framesTotal - """+Frame.verbFrequency.db_column+"""))+0.0 as p2 ,
                                (("""+Frame.frameFrequency.db_column+""")/framesTotal)+0.0 as p
                        FROM """+Frame._meta.db_table+""" 
                        JOIN (
                                SELECT SUM("""+Frame.frequency.db_column+""") AS framesTotal 
                                FROM """+Frame._meta.db_table+""") AS total
                       ) AS faux 
                  ON faux."""+Frame.id.db_column+""" = f."""+Frame.id.db_column+"""
              SET """+Frame.logLikelihoodRatio.db_column+""" = 
                                         2 * ( (k1*LOG10(p1)+(n1-k1)*LOG10(1-p1))
                                              +(k2*LOG10(p2)+(n2-k2)*LOG10(1-p2))
                                              -(k1*LOG10(p)+(n1-k1)*LOG10(1-p))
                                              -(k2*LOG10(p)+(n2-k2)*LOG10(1-p)))"""
    query = database.execute_sql(sql)
    
    sql = """ UPDATE """+Frame._meta.db_table+""" AS f
                JOIN  (
                        SELECT  MAX("""+Frame.logLikelihoodRatio.db_column+""") as maxLLR
                        FROM """+Frame._meta.db_table+""" 
                       ) AS faux 
              SET """+Frame.logLikelihoodRatio.db_column+""" =  """+Frame.logLikelihoodRatio.db_column+""" / maxLLR"""
    query = database.execute_sql(sql)
    
