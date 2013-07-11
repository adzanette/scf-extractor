#!/usr/bin/env python -W ignore::DeprecationWarning
# -*- coding: iso-8859-15 -*-
import pylab

## This class is used for build graphics
# @author Adriano Zanette
# @version 1.0
class Plotter():

  ## create a new label
  # @author Adriano Zanette
  # @version 1.0
  # @param xlabel String Axis X label
  # @param ylabel String Axis Y label
  def labels(self, xlabel, yLabel):
    pylab.xlabel(xlabel)
    pylab.ylabel(yLabel)

  ## insert a title
  # @author Adriano Zanette
  # @version 1.0
  # @param title String 
  def title(self, title):
    pylab.title(title)
  
  ## draw a line
  # @author Adriano Zanette
  # @version 1.0
  # @param x List Axis X values
  # @param y List Axis Y values
  # @param label String Line label
  def drawLine(self, x, y, label):
    pylab.plot(x, y,'-', label=label)
 
  ## draw bars 
  # @author Adriano Zanette
  # @version 1.0 
  # @param data List Bars values
  # @param color String bar color
  # @param edgecolor String edge bar color
  def drawBars(self, data, color='green', edgecolor = '#cccccc'):
    pylab.bar(range(0,len(data)), data, facecolor='green', edgecolor="#cccccc")
  
  ## show or print the graph
  # @author Adriano Zanette
  # @version 1.0
  # @param filename String If filename is passed, the graph will be saved
  # @return mixed
  def output(self, filename = None):
    pylab.legend(loc=(0.03,0.8))
    if filename:
      self.save(filename)
    else:
      self.show()

  ## show graph
  # @author Adriano Zanette
  # @version 1.0
  def show(self):
    pylab.show()

  ## save graph on a file
  # @author Adriano Zanette
  # @version 1.0
  # @param filename String
  def save(self, filename):
    pylab.savefig(filename)

