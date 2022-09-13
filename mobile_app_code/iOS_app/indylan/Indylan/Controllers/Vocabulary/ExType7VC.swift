//
//  ExType7VC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class ExType7VC: ExerciseController, UITableViewDelegate, UITableViewDataSource {

    //MARK: DECLARATION
    
    @IBOutlet var tblViewOption: TableView!
    
    @IBOutlet var btnMatchWord: UIButton!
    
    var arrType7Questions = Array<JSON>()

    var tempDict: JSON = [:]

    var dictSelectedOption: JSON = [:]

    var questionIndex = 0
    
    var score = 0

    var finalScore = 0

    var attempts = 0
    
    var totalOptions = 0

    var isOptionSelected = 0
    
    var selectedIndex = 123
    
    var selectedIndex1 = 123
    
    var wrongIndex = 123
    
    var wrongIndex1 = 123
    
    override func viewDidLoad() {
        super.viewDidLoad()

        self.title = selectedCategory

        if (UserDefaults.standard.object(forKey: "type7ScrollAlert")) == nil {
            UserDefaults.standard.set(true, forKey: "type7ScrollAlert")
            UserDefaults.standard.synchronize()
            
            Alert.showWith("", message: "Scroll down to see all language options".localized(), completion: nil)
        }
        
        tblViewOption.register(UINib(nibName: "CellExType1", bundle: nil), forCellReuseIdentifier: "cellExType1")
        
        self.updateQuestion()
    }

    //MARK: FUNCTIONS

    func updateQuestion() {
        self.view.isUserInteractionEnabled = true

        attempts = 0
        score = 0
        isOptionSelected = 0
        selectedIndex = 123
        selectedIndex1 = 123
        wrongIndex = 123
        wrongIndex1 = 123
        totalOptions = arrType7Questions[questionIndex]["option"].count
        dictSelectedOption.dictionaryObject?.removeAll()
        
        btnMatchWord.layer.borderColor = Colors.border.cgColor
        btnMatchWord.layer.borderWidth = 1
        btnMatchWord.layer.cornerRadius = 8
        btnMatchWord.setTitleColor(Colors.black, for: .normal)
        btnMatchWord.backgroundColor = Colors.white
        btnMatchWord.titleLabel?.font = Fonts.centuryGothic(ofType: .bold, withSize: 12)
        btnMatchWord.setTitle("matchWords".localized(), for: UIControlState.normal)
        view.bringSubview(toFront: btnMatchWord)
        
        tblViewOption.reloadData()
    }
    
    func wrongOptionSelected() {
        
        TapticEngine.notification.feedback(.error)
        
        self.view.isUserInteractionEnabled = false
        
        UIView.transition(with: btnMatchWord, duration: 0.3, options: .transitionCrossDissolve, animations: {
            self.btnMatchWord.backgroundColor = Colors.white
            self.btnMatchWord.setTitleColor(Colors.red, for: .normal)
            self.btnMatchWord.setTitle("retry".localized(), for: .normal)
        }, completion: { _ in
            
            DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                
                UIView.transition(with: self.btnMatchWord, duration: 0.3, options: .transitionCrossDissolve, animations: {
                    self.btnMatchWord.backgroundColor = Colors.white
                    self.btnMatchWord.setTitleColor(Colors.black, for: .normal)
                    self.btnMatchWord.setTitle("matchWords".localized(), for: .normal)
                }, completion: { _ in
                    self.view.isUserInteractionEnabled = true
                })
                
                self.wrongIndex = 123
                self.wrongIndex1 = 123
                self.tblViewOption.reloadData()
            }
        })
    }
    
    func rightOptionSelected()
    {
        TapticEngine.notification.feedback(.success)
        
        selectedIndex = 123
        selectedIndex1 = 123
        wrongIndex = 123
        wrongIndex1 = 123
        
        dictSelectedOption.dictionaryObject?.removeAll()
        
        if attempts == 2 {
            score += 1
        }
        
        attempts = 0

        self.view.isUserInteractionEnabled = false
        
        UIView.transition(with: btnMatchWord, duration: 0.3, options: .transitionCrossDissolve, animations: {
            self.btnMatchWord.backgroundColor = Colors.green
            self.btnMatchWord.setTitleColor(Colors.white, for: .normal)
            self.btnMatchWord.setTitle("correct".localized(), for: .normal)
        }, completion: { _ in
            
            DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                
                if (self.arrType7Questions.count > self.questionIndex + 1 || self.arrType7Questions[self.questionIndex]["option"].count > 0 || self.arrType7Questions[self.questionIndex]["option1"].count > 0)
                {
                    if self.arrType7Questions[self.questionIndex]["option"].count == 0 && self.arrType7Questions[self.questionIndex]["option1"].count == 0
                    {
                        self.questionIndex += 1
                        
                        UIView.animate(withDuration: 0.5, animations: {
                            let animation = CATransition()
                            animation.duration = 1.0
                            animation.startProgress = 0.0
                            animation.endProgress = 1.0
                            animation.timingFunction = CAMediaTimingFunction(name: kCAMediaTimingFunctionEaseOut)
                            animation.type = "pageCurl"
                            animation.subtype = "fromBottom"
                            animation.isRemovedOnCompletion = false
                            animation.fillMode = "extended"
                            self.view.layer.add(animation, forKey: "pageFlipAnimation")
                        })
                        
                        if self.score == self.totalOptions
                        {
                            self.finalScore += 1
                        }
                        
                        self.updateQuestion()
                    }
                    else
                    {
                        UIView.transition(with: self.btnMatchWord, duration: 0.3, options: .transitionCrossDissolve, animations: {
                            self.btnMatchWord.backgroundColor = Colors.white
                            self.btnMatchWord.setTitleColor(Colors.black, for: .normal)
                            self.btnMatchWord.setTitle("matchWords".localized(), for: .normal)
                        }, completion: { _ in
                            self.view.isUserInteractionEnabled = true
                        })
                        
                        self.tblViewOption.reloadData()
                    }
                }
                else
                {
                    if self.score == self.totalOptions  {
                        self.finalScore += 1
                    }
                    
                    let objExComplete = UIStoryboard.exercise.instantiateViewController(withClass: CongratsVC.self)!
                    objExComplete.score = self.finalScore
                    objExComplete.totalQuestions = self.arrType7Questions.count
                    self.navigationController?.pushViewController(objExComplete, animated: true)
                }
            }
        })
    }
    
    //MARK: UITABLEVIEW DELEGATE METHODS
    
    func numberOfSections(in tableView: UITableView) -> Int {
        2
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        arrType7Questions[questionIndex]["option"].count
    }

    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell
    {
        let cellOptions = tblViewOption.dequeueReusableCell(withIdentifier: "cellExType1", for: indexPath) as! CellExType1
        
        cellOptions.leadingConstraint.constant = 35
        cellOptions.trailingConstraint.constant = 35
        
        if indexPath.section == 0 {
            
            if selectedIndex == indexPath.row
            {
                cellOptions.optionView.state = .green
            }
            else if wrongIndex == indexPath.row
            {
                cellOptions.optionView.state = .red
                cellOptions.shake()
            }
            else
            {
                cellOptions.optionView.state = .blue
            }
            
            cellOptions.lblOption.text = "\(arrType7Questions[questionIndex]["option"][indexPath.row]["word"].stringValue)"
            
            return cellOptions
        }
        else
        {   
            if selectedIndex1 == indexPath.row
            {
                cellOptions.optionView.state = .green
            }
            else if wrongIndex1 == indexPath.row
            {
                cellOptions.optionView.state = .red
                cellOptions.shake()
            }
            else
            {
                cellOptions.optionView.state = .white
            }
            
            cellOptions.lblOption.text = "\(arrType7Questions[questionIndex]["option1"][indexPath.row]["word"].stringValue)"

            return cellOptions
        }
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath)
    {
        attempts += 1
        
        if indexPath.section == 0
        {
            let cell: CellExType1 = tblViewOption.cellForRow(at: IndexPath(row: indexPath.row, section: 0)) as! CellExType1
            
            if dictSelectedOption.count < 1
            {
                isOptionSelected += 1
                selectedIndex = indexPath.row
                
                cell.optionView.state = .green
                
                tempDict = arrType7Questions[questionIndex]["option"][indexPath.item]
                
                let wordId = arrType7Questions[questionIndex]["option"][indexPath.item]["word_id"]
                
                let wordDict = ["word" : arrType7Questions[questionIndex]["option"][indexPath.item]["word"], "word_id" : wordId, "isSelected" : "0"]
                
                dictSelectedOption.dictionaryObject?.removeAll()

                dictSelectedOption = JSON(wordDict)
            }
            else
            {
                if ((dictSelectedOption["isSelected"]).intValue == 0)
                {
                    attempts -= 1
                    selectedIndex = indexPath.row
                    tblViewOption.reloadData()
                    
                    tempDict = (arrType7Questions[questionIndex]["option"][indexPath.item])
                    
                    let word_id = arrType7Questions[questionIndex]["option"][indexPath.item]["word_id"]
                    
                    let wordDict = ["word" : arrType7Questions[questionIndex]["option"][indexPath.item]["word"], "word_id" : word_id, "isSelected" : "0"]
                    
                    dictSelectedOption.dictionaryObject?.removeAll()
                    
                    dictSelectedOption = JSON(wordDict)
                }
                else
                {
                    if (((dictSelectedOption["word_id"]).intValue) == ((arrType7Questions[questionIndex]["option"][indexPath.item]["word_id"]).intValue))
                     {
                        
                        if  arrType7Questions[questionIndex]["option1"].count == 1
                        {
                            arrType7Questions[questionIndex].arrayObject?.remove(at: 0)
                        }

                        if arrType7Questions[self.questionIndex]["option1"].arrayValue.contains(tempDict)
                        {
                            if let removeIndex = arrType7Questions[questionIndex]["option1"].arrayValue.index(of: tempDict)
                            {
                                arrType7Questions[questionIndex]["option1"].arrayObject?.remove(at: removeIndex)
                            }
                        }
                        
                        arrType7Questions[questionIndex]["option"].arrayObject?.remove(at: indexPath.row)
  
                        cell.optionView.state = .green
                        
                        self.rightOptionSelected()
                    }
                    else
                    {
                        wrongIndex = indexPath.row
                        tblViewOption.reloadData()
                        wrongOptionSelected()
                    }
                }
            }
        }
        else if indexPath.section == 1
        {
            let cell: CellExType1 = tblViewOption.cellForRow(at: IndexPath(row: indexPath.row, section: 1)) as! CellExType1
            
            if dictSelectedOption.count < 1
            {
                isOptionSelected += 1
                selectedIndex1 = indexPath.row
                tblViewOption.reloadData()
                
                tempDict = (arrType7Questions[questionIndex]["option1"][indexPath.item])

                let word_id = arrType7Questions[questionIndex]["option1"][indexPath.item]["word_id"]
                
                let wordDict = ["word" : arrType7Questions[questionIndex]["option1"][indexPath.item]["word"], "word_id" : word_id, "isSelected" : "1"]
                
                dictSelectedOption.dictionaryObject?.removeAll()
                
                dictSelectedOption = JSON(wordDict)
            }
            else
            {
                if ((dictSelectedOption["isSelected"]).intValue == 1)
                {
                    attempts -= 1
                    selectedIndex1 = indexPath.row
                    tblViewOption.reloadData()
                    
                    tempDict = (arrType7Questions[questionIndex]["option1"][indexPath.item])

                    let word_id = arrType7Questions[questionIndex]["option1"][indexPath.item]["word_id"]
                    
                    let wordDict = ["word" : arrType7Questions[questionIndex]["option1"][indexPath.item]["word"], "word_id" : word_id, "isSelected" : "1"]
                    
                    dictSelectedOption.dictionaryObject?.removeAll()
                    
                    dictSelectedOption = JSON(wordDict)
                }
                else
                {
                    if ((dictSelectedOption["word_id"]).intValue) == (arrType7Questions[questionIndex]["option1"][indexPath.item]["word_id"].intValue)
                    {
                        
                        if  arrType7Questions[questionIndex]["option1"].count == 1
                        {
                            arrType7Questions[questionIndex].arrayObject?.remove(at: 0)
                        }
                        arrType7Questions[questionIndex]["option1"].arrayObject?.remove(at: indexPath.row)

                        if arrType7Questions[questionIndex]["option"].arrayValue.contains(tempDict)
                        {
                            if let removeIndex = arrType7Questions[questionIndex]["option"].arrayValue.index(of: tempDict)
                            {
                                arrType7Questions[questionIndex]["option"].arrayObject?.remove(at: removeIndex)
                            }
                        }

                        cell.optionView.state = .green
                        
                        self.rightOptionSelected()
                    }
                    else
                    {
                        wrongIndex1 = indexPath.row
                        tblViewOption.reloadData()
                        wrongOptionSelected()
                    }
                }
            }
        }
    }
    
//MARK: BUTTON ACTIONS

    @IBAction func btnChooseWordClicked(_ sender: Any) {
        
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
}
