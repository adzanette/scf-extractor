from modules.Evaluator import Evaluator

## This command evaluates system's performance
# @author Adriano Zanette
# @version 0.1
class Evaluate:

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Evaluate
  def __init__(self):
    pass

  ## Execute the evaluation
  # @author Adriano Zanette
  # @version 0.1
  def run(self):
  
    evaluator = Evaluator()
    verbList = evaluator.verbList
    if len(verbList) == 0:
      evaluator.evaluate()
    elif len(verbList) == 1:
      evaluator.verbHistogram(verbList[0])
    else:
      evaluator.evaluateByVerbList(verbList)