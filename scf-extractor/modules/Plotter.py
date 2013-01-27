#!/usr/bin/env python -W ignore::DeprecationWarning
# -*- coding: iso-8859-15 -*-

from matplotlib.mlab import *
from matplotlib.pyplot import *

class Plotter():

  def labels(self, xlabel, yLabel):
    xlabel(xlabel)
    ylabel(yLabel)

  def drawLine(self, x, y, label):
    plot(x, y,'-', label)

  def show(self):
    show()

  def save(filename):
    savefig(filename)

