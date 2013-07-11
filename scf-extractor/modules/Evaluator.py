from modules.Configuration import config
from models.scf import Frame, ReferenceFrame, Verb, database
from modules.Plotter import Plotter
from filter import Filter

## This class is used for evaluating system performance
# @author Adriano Zanette
# @version 1.0
class Evaluator():

  ## class constructor
  # @author Adriano Zanette
  # @version 1.0
  # @return Evaluator
  def __init__ (self):        
    self.filter = config.evaluator.filter
    self.value = config.evaluator.minValue
    self.max = config.evaluator.maxValue
    self.increment = config.evaluator.increment
    self.operator = config.evaluator.operator
    self.output = config.evaluator.output
    self.verbList = config.evaluator.verbList
    self.values = []
    self.precisionValues = []
    self.recallValues = []
    self.fmeasureValues = []

  ## add values to a result vector to build a graph
  # @author Adriano Zanette
  # @version 1.0
  # @param value float
  # @param precision float
  # @param recall float
  # @param fmeasure float
  def addValue(self, value, precision, recall, fmeasure):
    self.values.append(value)
    self.precisionValues.append(precision)
    self.recallValues.append(recall)
    self.fmeasureValues.append(fmeasure)

  ## draw a verb histogram of scf frequencies
  # @author Adriano Zanette
  # @version 1.0
  # @param verbString String 
  def verbHistogram(self, verbString):
    verb = Verb.get(Verb.verb == verbString)
    frequencies = [frame.frequency for frame in verb.frames if frame.frequency > self.value ]
    frequencies.sort(reverse=True)

    plotter = Plotter()
    plotter.drawBars(frequencies, edgecolor="#cccccc")
    plotter.title('Verb '+verbString+' Histogram')
    plotter.labels("Frames", 'Frequency')
    plotter.output()

  ## evaluates system's performance
  # @author Adriano Zanette
  # @version 1.0
  # @param verbList If passed evaluate only verbs in the list
  def evaluate(self, verbList = None):
    filterModule = Filter()
    self.queries = self.buildQueries(verbList)

    while(self.value <= self.max):
      filterModule.setComparator(self.filter, self.operator, self.value)
      filterModule.filterFrames()
      
      golden = self.countGoldenFrames()
      retrieved = self.countNotFilteredFrames()
      intersect = self.countIntersection()

      #print 'value: %s, ints: %s, retr: %s, gold: %s ' % (str(self.value), str(intersect), str(retrieved), str(golden))
      p = self.precision(intersect, retrieved)
      r = self.recall(intersect, golden)
      f = self.fmeasure(p, r)
      #print 'value: %s, p: %s, r: %s, f: %s ' % (str(self.value), str(p), str(r), str(f))
      print '%s,%s,%s,%s' % (str(self.value), str(p), str(r), str(f))

      self.addValue(self.value, p, r, f)
      self.value += self.increment
      
    self.plotEvaluation()  

  ## build queries for future searches
  # @author Adriano Zanette
  # @version 1.0
  # @param verbList If passed restricts SQL only for verbs in the list
  # @return Dict Queries for golden, extract and intersection
  def buildQueries(self, verbList = None):
    
    verbRestriction = self.buildVerbListRestriction(verbList)

    goldenSQL = """ SELECT COUNT(*) 
                    FROM """+ReferenceFrame._meta.db_table + """ AS rf 
                    WHERE """+Verb.id.db_column+""" in 
                          ( SELECT DISTINCT("""+Verb.id.db_column+""") 
                            FROM """+Frame._meta.db_table+ """ AS f 
                            WHERE f."""+Frame.filtered.db_column+""" = 0 
                            """+ verbRestriction +""")""" 

    intersectionSQL = """ SELECT COUNT(*) 
                          FROM """+ReferenceFrame._meta.db_table + """ AS rf 
                          JOIN """+Frame._meta.db_table+""" AS f 
                            ON f."""+Frame.verb.db_column+""" = rf."""+ReferenceFrame.verb.db_column+""" 
                            AND f."""+Frame.frame.db_column+""" = rf."""+ReferenceFrame.frame.db_column+"""
                            AND rf."""+Frame.isPassive.db_column+""" = f."""+ReferenceFrame.isPassive.db_column+"""
                          WHERE f."""+Frame.filtered.db_column+""" = 0 """ + verbRestriction

    extractedSQL =  "SELECT COUNT(*) FROM "+Frame._meta.db_table + " AS f WHERE "+Frame.filtered.db_column+" = 0 " + verbRestriction

    return {'golden': goldenSQL, 'intersection': intersectionSQL, 'extracted': extractedSQL}

  ## build restriction for verblist
  # @author Adriano Zanette
  # @version 1.0
  # @param verbList If passed builds SQL restriction for verbs in the list
  # @return string 
  def buildVerbListRestriction(self, verbList):
    if verbList:
      inSQL = ["\'%s\'" % (verb) for verb in verbList]
      sqlVerbs = "SELECT "+Verb.id.db_column+" FROM "+Verb._meta.db_table+" WHERE "+Verb.verb.db_column+" in ( "+ (",".join(inSQL)) +")"
      verbIds = [ str(row[0]) for row in database.execute_sql(sqlVerbs).fetchall() ]
      restriction = " AND f."+Verb.id.db_column+" IN ( "+ ",".join(verbIds) +" ) "
    else:
      restriction = ""

    return restriction

  ## calculates precision
  # @author Adriano Zanette
  # @version 1.0
  # @param intersect int Number of SCF extracted correct
  # @param retrieved int Number of SCF extracted
  # @return float
  def precision(self, intersect, retrieved):
    if intersect == 0 :
      return 0
    
    return (float(intersect)/float(retrieved))*100

  ## calculates recall
  # @author Adriano Zanette
  # @version 1.0
  # @param intersect int Number of SCF extracted correct
  # @param golden int Number of reference SCF
  # @return float
  def recall(self, intersect, golden):
    if intersect == 0 :
      return 0
    
    return (float(intersect)/float(golden))*100

  ## calculates f-measure
  # @author Adriano Zanette
  # @version 1.0
  # @param precistion float
  # @param recall float
  # @return float
  def fmeasure(self, precision, recall):
    if precision == 0 or recall == 0 :
      return 0
    
    return (2*precision*recall)/(precision+recall)
  
  ## retrieve the number of golden frames not filtered
  # @author Adriano Zanette
  # @version 1.0
  # @return Integer 
  def countGoldenFrames(self):
    sql = self.queries['golden']
    result = database.execute_sql(sql)
    return result.fetchone()[0]

  ## retrieve the size of intersection between golden frames and frames extracted not filtered
  # @author Adriano Zanette
  # @version 1.0
  # @return Integer 
  def countIntersection(self):
    sql = self.queries['intersection']
    result = database.execute_sql(sql)
    return result.fetchone()[0]

  ## retrieve the number of frames extracted not filtered
  # @author Adriano Zanette
  # @version 1.0
  # @return Integer 
  def countNotFilteredFrames(self):
    sql = self.queries['extracted']
    result = database.execute_sql(sql)
    return result.fetchone()[0]

  ## plot evaluation
  # @author Adriano Zanette
  # @version 1.0
  def plotEvaluation(self):
    plotter = Plotter()
    plotter.drawLine(self.values, self.precisionValues, 'precision')
    plotter.drawLine(self.values, self.recallValues, 'recall')
    plotter.drawLine(self.values, self.fmeasureValues, 'fmeasure')
    plotter.title('SCFExtractor Evaluation')
    plotter.labels("Cutoff", '%')
    if self.output:
      plotter.output(self.output)
    else:
      plotter.show()