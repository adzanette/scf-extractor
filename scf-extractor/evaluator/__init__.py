
from modules.Configuration import *
import MySQLdb
    

class Evaluator():

  def __init__ (self):
    self.conn = MySQLdb.connect('localhost', 'root', 'zanette', 'scf-teste')
    self.cursor = self.conn.cursor()
    self.reference_table = config.evaluator.scfReferenceTable

    self.filter_value   = [] 
    self.precision    = []  
    self.recall     = []  
    self.fmeasure     = []  

  def verbHistogram(self, verbString, cutoff=10, output=None):

    import pylab
    from models.scf import Verb
    
    verb = Verb.get(Verb.verb == verbString)
    frequencies = [frame.frequency for frame in verb.frames if frame.frequency > cutoff ]
    frequencies.sort(reverse=True)

    pylab.bar(range(0,len(frequencies)), frequencies, facecolor='green', edgecolor="#cccccc")
    pylab.title('Verb '+verbString+' Histogram')
    pylab.xlabel("Frames")
    pylab.ylabel('Frequency')
    if output:
      pylab.savefig(output)
    else:
      pylab.show()

  def evaluate(self,filter_type, max_val, increment, frel, fabsf, fabsv):
    
    filter_type = filter_type.lower()

    try:
      i = eval(filter_type)
    except:
      print "Invalid filter type!"
      return -1   

    while(eval(filter_type) <= max_val):

      sql = """ SELECT COUNT(*) AS gold 
            FROM """ + self.reference_table + """ as r INNER JOIN
            verbs v on v.id_verb = r.id_verb 
            WHERE v.frequency >= """+str(fabsv)
      self.cursor.execute(sql)
      row = self.cursor.fetchone()
      golden = row[0]

      sql = """ SELECT  COUNT(*) AS retr 
            FROM  frames
            WHERE relative_frequency >= """+ str(frel) + """
              AND verb_frequency >= """+ str(fabsv) +"""
              AND frequency >= """+ str(fabsf)
      self.cursor.execute(sql)
      row = self.cursor.fetchone()
      retrieved = row[0]

      sql = """ SELECT count(*) AS intersec
            FROM """ + self.reference_table + """ AS r INNER JOIN
              (SELECT id_frame, frame, id_verb
               FROM frames
               WHERE relative_frequency >= """+ str(frel) +"""
                AND verb_frequency >= """+ str(fabsv) +""" 
                AND frequency >= """+ str(fabsf) +""" ) AS f
            ON f.id_verb = r.id_verb AND f.frame = r.frame"""
      self.cursor.execute(sql)
      row = self.cursor.fetchone()
      intersect = row[0]

      p = float(intersect)/float(retrieved)
      r = float(intersect)/float(golden)
      f = (2*p*r)/(p+r) 

      self.filter_value.append(eval(filter_type))
      self.precision.append(p)
      self.recall.append(r)
      self.fmeasure.append(f)

      if filter_type == "frel":
        frel += increment
      elif filter_type == "fabsf":
        fabsf += increment
      elif filter_type == "fabsv":
        fabsv += increment
  

  def plot(self, deflabel= 'Teste', output=None):

    from matplotlib.mlab import *
    from matplotlib.pyplot import *

    plot(self.filter_value, self.precision,'-', label='precision')
    plot(self.filter_value, self.recall,'-', label='recall')
    plot(self.filter_value, self.fmeasure,'-', label='fmeasure')

    legend(loc=(0.03,0.8))
    xlabel(deflabel)
    ylabel('%')
    show()
    #savefig(out)

  def get_filter_values(self):
    return [self.filter_value, self.precision, self.recall, self.fmeasure]
  