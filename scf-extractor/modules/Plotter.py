#!/usr/bin/env python -W ignore::DeprecationWarning
# -*- coding: iso-8859-15 -*-

import pylab

class Plotter():

  def labels(self, xlabel, yLabel):
    pylab.xlabel(xlabel)
    pylab.ylabel(yLabel)

  def title(self, title):
    pylab.title(title)

  def drawLine(self, x, y, label):
    pylab.plot(x, y,'-', label)

  def drawBars(self, data, color='green', edgecolor = '#cccccc'):
    pylab.bar(range(0,len(data)), data, facecolor='green', edgecolor="#cccccc")
  
  def output(self, filename = None):
    pylab.legend(loc=(0.03,0.8))
    if filename:
      self.save(filename)
    else:
      self.show()

  def show(self):
    pylab.show()

  def save(filename):
    pylab.savefig(filename)

