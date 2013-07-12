from reader.FrameDatabaseIterator import Iterator as FrameIterator
from models.scf import Frame, database
import math

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
    
    query = database.execute_sql("""
      SELECT SUM("""+Frame.frequency.db_column+""") AS totalFrequency 
      FROM """+Frame._meta.db_table)
    totalFrequency = query.fetchall()[0][0]
    
    for frame in FrameIterator():
      k1 = float(frame.frequency)
      n1 = float(frame.verbFrequency)
      k2 = float(frame.frameFrequency) - frame.frequency
      n2 = float(totalFrequency) - frame.verbFrequency

      p1 = k1/n1 #relative frequency
      p2 = k2/n2
      p = (k1 + k2) / (n1 + n2)
      
      if k2 == 0 or p1 == 1:
        frame.logLikelihoodRatio = 1
      else:
        frame.logLikelihoodRatio = 2 * (LogLikelihood.logL(p1,k1,n1) + LogLikelihood.logL(p2,k2,n2) - LogLikelihood.logL(p,k1,n1) - LogLikelihood.logL(p,k2,n2))
  
      frame.save()

  ## Calculates logL
  # @author Adriano Zanette
  # @version 1.0
  @staticmethod
  def logL(p, k, n):
    return k * math.log(p, 10) + (n -k) * math.log(1-p, 10)


    
    