//
//  ExType15QuestinsVC.swift
//  Indylan
//
//  Created by Bhavik Thummar on 10/5/20.
//  Copyright © 2020 Bhavik Thummar. All rights reserved.
//

import UIKit

class ExType15QuestinsVC: ExerciseController, UITableViewDelegate, UITableViewDataSource {
    
    @IBOutlet var scrView: UIScrollView!
    
    @IBOutlet var lblQuestion: UILabel!
    
    @IBOutlet var tblViewOptions: UITableView!
    
    @IBOutlet var btnChooseOption: UIButton!
    @IBOutlet weak var btnNote: ThemeButton!
    
    var arrType15 = Array<JSON>()

    var questionIndex = 0
    
    var attempts = 0
    
    override func viewDidLoad() {
        super.viewDidLoad()

        questionIndex = resumeIndex
        self.automaticallyAdjustsScrollViewInsets = false

        isUpdateQuestion = false

        lblQuestion.textColor = Colors.black
        lblQuestion.font = Fonts.centuryGothic(ofType: .bold, withSize: 14)

        self.title = selectedCategory
        
        if (UserDefaults.standard.object(forKey: "type15QAScrollAlert")) == nil {
            UserDefaults.standard.set(true, forKey: "type15QAScrollAlert")
            UserDefaults.standard.synchronize()

            Alert.showWith("", message: "Scroll down to see all language options".localized(), completion: nil)
        }
        
        tblViewOptions.register(UINib(nibName: "CellExType1", bundle: nil), forCellReuseIdentifier: "cellExType1")
        
        tblViewOptions.estimatedRowHeight = 70
        tblViewOptions.rowHeight = UITableViewAutomaticDimension
        
        btnChooseOption.isUserInteractionEnabled = false
        btnChooseOption.layer.borderColor = Colors.border.cgColor
        btnChooseOption.layer.borderWidth = 1
        btnChooseOption.layer.cornerRadius = 8
        btnChooseOption.setTitleColor(Colors.black, for: .normal)
        btnChooseOption.backgroundColor = Colors.white
        btnChooseOption.titleLabel?.font = Fonts.centuryGothic(ofType: .bold, withSize: 12)
        btnChooseOption.setTitle("chooseOption".localized(), for: .normal)
        view.bringSubview(toFront: btnChooseOption)
        
        btnNote.layer.cornerRadius = 4
        btnNote.tintColor = Colors.white
        
        self.updateQuestion()
    }

    override func viewDidDisappear(_ animated: Bool) {
        super.viewDidDisappear(animated)
        resumeIndex = questionIndex
        isUpdateQuestion = false
    }

    func updateQuestion() {
        self.view.isUserInteractionEnabled = true
        
        attempts = 0
        
        btnChooseOption.setTitleColor(Colors.black, for: .normal)
        btnChooseOption.backgroundColor = Colors.white
        btnChooseOption.setTitle("chooseOption".localized(), for: UIControlState.normal)
        
        lblQuestion.text = "\(arrType15[questionIndex]["word"].stringValue)"
        
        tblViewOptions.reloadData()
        tblViewOptions.scrollToRow(at: IndexPath.init(row: 0, section: 0), at: UITableViewScrollPosition.top, animated: true)
        
        scrView.setContentOffset(CGPoint.zero, animated: true)
        
        if (arrType15[questionIndex]["notes"].stringValue.isEmpty)
        {
            btnNote.isHidden = true
        }
        else
        {
            btnNote.isHidden = false
        }
    }
    
    // MARK: UITABLEVIEW DELEGATE METHODS

    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        arrType15[questionIndex]["option"].count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        UITableViewAutomaticDimension
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cellOptions = tblViewOptions.dequeueReusableCell(withIdentifier: "cellExType1") as! CellExType1
        cellOptions.optionView.state = .normal
        cellOptions.lblOption.text = "\(arrType15[questionIndex]["option"][indexPath.row]["word"].stringValue)"
        return cellOptions
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        let cellOptions = tblViewOptions.cellForRow(at: indexPath) as! CellExType1
        
        attempts += 1
        
        if arrType15[questionIndex]["option"][indexPath.row]["is_correct"].intValue == 1
        {
            if attempts == 1 {
                cultureScore += 1
            }
            
            TapticEngine.notification.feedback(.success)
            
            self.view.isUserInteractionEnabled = false
            
            UIView.animate(withDuration: 0.3, delay: 0.0, animations: {
                cellOptions.optionView.state = .green
            },  completion: nil)
            
            UIView.animate(withDuration: 0.5, delay: 0.0, animations: {
                self.btnChooseOption.backgroundColor = Colors.green
                self.btnChooseOption.setTitleColor(Colors.white, for: .normal)
                self.btnChooseOption.setTitle("correct".localized(), for: .normal)
            }, completion: { (Bool) -> Void in
                self.questionIndex += 1
                
                self.view.isUserInteractionEnabled = true
                
                DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                    if self.arrType15.count > self.questionIndex {
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
                        
                        self.updateQuestion()
                    }
                    else
                    {
                        paraIndex += 1

                        if arrType15Questions.count > paraIndex
                        {
                            self.questionIndex = 0
                            isUpdateQuestion = true
                            self.navigationController?.popViewController(animated: true)
                        }
                        else
                        {
                            let objExComplete = UIStoryboard.exercise.instantiateViewController(withClass: CongratsVC.self)!
                            objExComplete.score = cultureScore
                            objExComplete.totalQuestions = cultureQuestionCount
                            self.navigationController?.pushViewController(objExComplete, animated: true)
                        }
                    }
                }
            })
            
        }
        else
        {
            TapticEngine.notification.feedback(.error)
            
            cellOptions.shake()
            
            self.view.isUserInteractionEnabled = false
            
            UIView.transition(with: btnChooseOption, duration: 0.3, options: .transitionCrossDissolve, animations: {
                self.btnChooseOption.backgroundColor = Colors.white
                self.btnChooseOption.setTitleColor(Colors.red, for: .normal)
                self.btnChooseOption.setTitle("retry".localized(), for: .normal)
            }, completion: { _ in
                
                DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                    
                    UIView.transition(with: self.btnChooseOption, duration: 0.3, options: .transitionCrossDissolve, animations: {
                        self.btnChooseOption.backgroundColor = Colors.white
                        self.btnChooseOption.setTitleColor(Colors.black, for: .normal)
                        self.btnChooseOption.setTitle("chooseOption".localized(), for: .normal)
                    }, completion: { _ in
                        self.view.isUserInteractionEnabled = true
                    })
                }
            })
            
            UIView.animate(withDuration: 0.3, delay: 0.0, animations: {
                cellOptions.optionView.state = .red
            },  completion: { (Bool) -> Void in
                
                DispatchQueue.main.asyncAfter(deadline: .now() + 0.5) {
                    UIView.animate(withDuration: 0.3, delay: 0.0, animations: {
                        cellOptions.optionView.state = .normal
                    })
                }
            })
        }
    }

// MARK: Button Actions
    
    @IBAction func btnContinueClicked(_ sender: Any) {
        
    }
    
    @IBAction func btnNoteAction(_ sender: ThemeButton) {
        let note = arrType15[questionIndex]["notes"].stringValue
        guard !note.isEmpty else { return }
        Alert.showWith(message: note, completion: nil)
    }
    
    @IBAction func btnBackClicked(_ sender: Any) {
        resumeIndex = questionIndex
        isUpdateQuestion = false
        self.navigationController?.popViewController(animated: true)
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

}
