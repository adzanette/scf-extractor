from modules.Configuration import config
from models.scf import Frame, ReferenceFrame, Verb, database
from modules.Plotter import Plotter
from filter import Filter

class Evaluator():

  def __init__ (self):        
    self.filter = config.evaluator.filter
    self.value = config.evaluator.minValue
    self.max = config.evaluator.maxValue
    self.increment = config.evaluator.increment
    self.operator = config.evaluator.operator
    self.output = config.evaluator.output
    self.verbList = config.evaluator.verbList
    self.queries = self.buildQueries()

  def verbHistogram(self, verbString):

    verb = Verb.get(Verb.verb == verbString)
    frequencies = [frame.frequency for frame in verb.frames if frame.frequency > self.value ]
    frequencies.sort(reverse=True)

    plotter = Plotter()
    plotter.drawBars(frequencies, edgecolor="#cccccc")
    plotter.title('Verb '+verbString+' Histogram')
    plotter.labels("Frames", 'Frequency')
    plotter.output()

  def evaluateByVerbList(self,verbList):
    
    self.values = []
    self.precision = []
    self.recall = []
    self.fmeasure = []

    filters = Filter()
    filters.filterVerbs(verbList)

    golden = ReferenceFrame.select().join(Verb).where(Verb.filtered == False).count()

    while(self.value <= self.max):

      filters.setComparator(self.filter, self.operator, self.value)
      filters.filterFrames()
      
      retrieved = Frame.select().where(Frame.filtered == False).count()
      intersect = Frame.select().join(Verb).join(ReferenceFrame).where(Frame.verb == ReferenceFrame.verb, Frame.frame == ReferenceFrame.frame, Frame.filtered == False).count()

      p = float(intersect)/float(retrieved)
      r = float(intersect)/float(golden)
      f = (2*p*r)/(p+r) 
      print 'value: %s, p: %s, r: %s, f: %s ' % (str(self.value), str(p), str(r), str(f))

      self.values.append(self.value)
      self.precision.append(p)
      self.recall.append(r)
      self.fmeasure.append(f)

      self.value += self.increment
      
    self.plot()  

  def evaluate(self):
    
    self.values = []
    self.precision = []
    self.recall = []
    self.fmeasure = []

    verbFilter = 1

    filters = Filter()

    while(self.value <= self.max):

      filters.setComparator(self.filter, self.operator, self.value)
      filters.filterFrames()
      
      golden = self.countGoldenFrames()
      retrieved = self.countNotFilteredFrames()
      intersect = self.countIntersection()

      #print 'value: %s, ints: %s, retr: %s, gold: %s ' % (str(self.value), str(intersect), str(retrieved), str(golden))
      p = float(intersect)/float(retrieved)
      r = float(intersect)/float(golden)
      f = (2*p*r)/(p+r) 
      #print 'value: %s, p: %s, r: %s, f: %s ' % (str(self.value), str(p), str(r), str(f))
      print '%s,%s,%s,%s' % (str(self.value), str(p*100), str(r*100), str(f*100))

      self.values.append(self.value)
      self.precision.append(p)
      self.recall.append(r)
      self.fmeasure.append(f)

      self.value += self.increment
      
    self.plot()  

  
  def buildQueries(self):
    goldenSQL = """ SELECT COUNT(*) 
                    FROM """+ReferenceFrame._meta.db_table + """ AS rf 
                    WHERE """+Verb.id.db_column+""" in 
                          ( SELECT DISTINCT("""+Verb.id.db_column+""") 
                            FROM """+Frame._meta.db_table+ """ AS f 
                            WHERE f."""+Frame.filtered.db_column+""" = 0 )"""

    intersectionSQL = """ SELECT COUNT(*) 
                          FROM """+ReferenceFrame._meta.db_table + """ AS rf 
                          JOIN """+Frame._meta.db_table+""" AS f 
                            ON f."""+Frame.verb.db_column+""" = rf."""+ReferenceFrame.verb.db_column+""" 
                            AND f."""+Frame.frame.db_column+""" = rf."""+ReferenceFrame.frame.db_column+"""
                            AND rf."""+Frame.isPassive.db_column+""" = f."""+ReferenceFrame.isPassive.db_column+"""
                          WHERE f."""+Frame.filtered.db_column+""" = 0 """

    extractedSQL =  "SELECT COUNT(*) FROM "+Frame._meta.db_table + " AS f WHERE "+Frame.filtered.db_column+" = 0"

    return {'golden': goldenSQL, 'intersection': intersectionSQL, 'extracted': extractedSQL}

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

  def plot(self):
    plotter = Plotter()
    plotter.drawLine(self.values, self.precision, 'precision')
    plotter.drawLine(self.values, self.recall, 'recall')
    plotter.drawLine(self.values, self.fmeasure, 'fmeasure')
    plotter.title('SCFExtractor Evaluation')
    plotter.labels("Cutoff", '%')
    if self.output:
      plotter.output(self.output)
    else:
      plotter.show()